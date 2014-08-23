<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

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

        foreach ($this->matcherChain as $group => $matcher) {
            $bestMatches[$group] = $matcher->create($request);
        }

        $template = $this->findTemplate($request, $_format, $code, $this->debug);

        $response = new Response($this->twig->render(
            $template,
            array(
                'status_code'    => $code,
                'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception'      => $exception,
                'logger'         => $logger,
                'currentContent' => $currentContent,
                'best_matches'   => $bestMatches,
            )
        ));
        $response->setStatusCode($code);

        return $response;
    }

    /**
     * @param BestMatcherInterface      $matcher
     * @param string                    $group   Unique per Chain.
     * @throws InvalidArgumentException
     */
    public function addMatcher(BestMatcherInterface $matcher, $group)
    {
        if (array_key_exists($group, $this->matcherChain)) {
            throw new InvalidArgumentException(sprintf('You can only add on matcher with group %s.', $group));
        }

        $this->matcherChain[$group] = $matcher;
    }
}
