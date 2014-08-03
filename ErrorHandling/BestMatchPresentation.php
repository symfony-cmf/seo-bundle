<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\ErrorHandling;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * This presentation model enriches the the values to render the
 * error pages by the help of so called BestMatcher.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class BestMatchPresentation extends ExceptionController
{
    /**
     * Type/shortcut for a best matcher handling the parent.
     */
    const MATCH_TYPE_PARENT = 'parent';

    /**
     * Type/shortcut for best matcher handling the ancestors
     */
    const MATCH_TYPE_ANCESTOR = 'ancestor';

    /**
     * Chain of matcher.
     *
     * @var array|BestMatcherInterface[]
     */
    protected $matcherChain = array();

    /**
     * @param Request              $request
     * @param FlattenException     $exception
     * @param DebugLoggerInterface $logger
     * @param string               $_format
     * @return Response
     */
    public function showAction(
        Request $request,
        FlattenException $exception,
        DebugLoggerInterface $logger = null,
        $_format = 'html'
    ) {
        $code = $exception->getStatusCode();

        if (404 !== $code) {
            return parent::showAction($request, $exception, $logger, $_format);
        }

        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
        $bestMatches = array();

        foreach ($this->matcherChain as $type => $matcher) {
            $bestMatches[$type] = $matcher->create($request);
        }

        return new Response($this->twig->render(
            $this->findTemplate($request, $_format, $code, $this->debug),
            array(
                'status_code'    => $code,
                'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception'      => $exception,
                'logger'         => $logger,
                'currentContent' => $currentContent,
                'best_matches'   => $bestMatches,
            )
        ));
    }

    /**
     * @param BestMatcherInterface      $matcher
     * @param string                    $type
     * @throws InvalidArgumentException
     */
    public function addMatcher(BestMatcherInterface $matcher, $type)
    {
        if (self::MATCH_TYPE_ANCESTOR !== $type && self::MATCH_TYPE_PARENT !== $type) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s is not supported as a matcher type. use one of %s or %s',
                    $type,
                    self::MATCH_TYPE_PARENT,
                    self::MATCH_TYPE_ANCESTOR
                )
            );
        }

        if (array_key_exists($type, $this->matcherChain)) {
            throw new InvalidArgumentException(sprintf('You can only add on matcher with type %s.', $type));
        }

        $this->matcherChain[$type] = $matcher;
    }
}
