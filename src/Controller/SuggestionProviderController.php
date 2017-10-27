<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Cmf\Bundle\SeoBundle\SuggestionProviderInterface;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
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
    protected $suggestionProviders = [];

    /**
     * Contains the list of templates defined in the error section of the
     * cmf_seo configuration.
     *
     * Looks like
     *
     *  array(
     *      'format' => 'MyBundle:Error:exception.format.twig,
     *  )
     *
     * @var array
     */
    private $templates;

    /**
     * @var RequestMatcherInterface
     */
    private $exclusionRequestMatcher;

    /**
     * @param \Twig_Environment       $twig
     * @param bool                    $debug
     * @param RequestMatcherInterface $requestMatcher     The exclusion matcher to decider whether a route should be handled
     *                                                    by this error handling. It uses the defined exclusion_rules in the
     *                                                    error configuration.
     * @param array                   $templates          containing the configured templates to use in custom error cases
     * @param array                   $suggestionProvider A list of provider and group pairs
     */
    public function __construct(
        \Twig_Environment $twig,
        $debug,
        RequestMatcherInterface $requestMatcher,
        $templates,
        $suggestionProvider
    ) {
        $this->templates = $templates;
        $this->exclusionRequestMatcher = $requestMatcher;
        $this->suggestionProviders = $suggestionProvider;

        parent::__construct($twig, $debug);
    }

    public function listAction(
        Request $request,
        FlattenException $exception,
        DebugLoggerInterface $logger = null
    ) {
        $code = $exception->getStatusCode();
        if (404 !== $code || $this->exclusionRequestMatcher->matches($request)) {
            return $this->showAction($request, $exception, $logger);
        }

        $templateForSuggestion = $this->getTemplateForSuggestions($request->getRequestFormat());
        if (null === $templateForSuggestion) {
            return $this->showAction($request, $exception, $logger);
        }

        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
        $groupedSuggestions = [];

        foreach ($this->suggestionProviders as $item) {
            $suggestions = $item['provider']->create($request);
            $groupedSuggestions[$item['group']] = isset($groupedSuggestions[$item['group']])
                ? array_merge($groupedSuggestions[$item['group']], $suggestions)
                : $suggestions;
        }

        return new Response(
            $this->twig->render(
                $templateForSuggestion,
                [
                    'status_code' => $code,
                    'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                    'message' => $exception->getMessage(),
                    'exception' => $exception,
                    'logger' => $logger,
                    'currentContent' => $currentContent,
                    'best_matches' => $groupedSuggestions,
                ]
            ),
            $code
        );
    }

    /**
     * You can define your templates for each format in the bundle's configuration.
     *
     * @param string $format
     *
     * @return string
     */
    private function getTemplateForSuggestions($format = 'html')
    {
        if (!isset($this->templates[$format])) {
            return;
        }

        return $this->templates[$format];
    }
}
