<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Cmf\Bundle\SeoBundle\SuggestionProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * This presentation model enriches the the values to render the
 * error pages by the help of so called SuggestionProviders.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SuggestionProviderController extends ExceptionController
{
    /**
     * Chain of suggestion providers.
     *
     * @var array|SuggestionProviderInterface[]
     */
    protected $suggestionProviders = array();

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
        $groupedSuggestions = array();

        foreach ($this->suggestionProviders as $item) {
            $suggestions = $item['provider']->create($request);
            $groupedSuggestions[$item['group']] = isset($groupedSuggestions[$item['group']])
                ? array_merge($groupedSuggestions[$item['group']], $suggestions)
                : $suggestions;
        }

        return new Response(
            $this->twig->render(
                $this->findTemplate($request, $_format, $code, $this->debug),
                array(
                    'status_code'    => $code,
                    'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                    'exception'      => $exception,
                    'logger'         => $logger,
                    'currentContent' => $currentContent,
                    'best_matches'   => $groupedSuggestions,
                )
            ),
            $code
        );
    }

    /**
     * @param SuggestionProviderInterface $matcher
     * @param string                      $group
     */
    public function addSuggestionProvider(SuggestionProviderInterface $matcher, $group)
    {
        $this->suggestionProviders[] = array('provider' => $matcher, 'group' => $group);
    }
}
