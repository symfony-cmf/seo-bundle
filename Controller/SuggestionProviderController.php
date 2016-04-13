<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Cmf\Bundle\SeoBundle\SuggestionProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
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
     * @param RequestMatcherInterface $requestMatcher The exclusion matcher to decider whether a route should be handled
     *                                                by this error handling. It uses the defined exclusion_rules in the
     *                                                error configuration.
     * @param array                   $templates      Containing the configured templates to use in custom error cases.
     */
    public function __construct(
        \Twig_Environment $twig,
        $debug,
        RequestMatcherInterface $requestMatcher,
        $templates
    ) {
        $this->templates = $templates;
        $this->exclusionRequestMatcher = $requestMatcher;

        parent::__construct($twig, $debug);
    }

    /**
     * @param Request              $request
     * @param FlattenException     $exception
     * @param DebugLoggerInterface $logger
     * @param string               $_format
     *
     * @return Response
     */
    public function showAction(
        Request $request,
        FlattenException $exception,
        DebugLoggerInterface $logger = null,
        $_format = 'html'
    ) {
        $code = $exception->getStatusCode();
        if (404 !== $code || $this->exclusionRequestMatcher->matches($request)) {
            return parent::showAction($request, $exception, $logger, $_format);
        }

        $templateForSuggestion = $this->getTemplateForSuggestions($_format);
        if (null === $templateForSuggestion) {
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
                $templateForSuggestion,
                array(
                    'status_code' => $code,
                    'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                    'message' => $exception->getMessage(),
                    'exception' => $exception,
                    'logger' => $logger,
                    'currentContent' => $currentContent,
                    'best_matches' => $groupedSuggestions,
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
