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

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * Controller to handle requests for sitemap.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapController
{
    /**
     * @var UrlInformationProviderInterface
     */
    private $urlProvider;
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var array
     */
    private $templates;

    /**
     * You should provide templates for html and xml.
     *
     * Json is serialized by default, but can be customized with a template
     *
     * @param UrlInformationProviderInterface $provider
     * @param EngineInterface                 $templating
     * @param array                           $templates  Hash map with key being the format,
     *                                                    value the name of the twig template
     *                                                    to render the sitemap in that format
     */
    public function __construct(
        UrlInformationProviderInterface $provider,
        EngineInterface $templating,
        array $templates
    ) {
        $this->urlProvider = $provider;
        $this->templating = $templating;
        $this->templates = $templates;
    }

    /**
     * @param string $_format The format of the sitemap.
     *
     * @return Response
     */
    public function indexAction($_format)
    {
        $supportedFormats = array_merge(array('json'), array_keys($this->templates));
        if (!in_array($_format, $supportedFormats)) {
            $text = sprintf(
                'Unknown format %s, use one of %s.',
                $_format,
                implode(', ', $supportedFormats)
            );

            return new Response($text, 406);
        }

        $urls = $this->urlProvider->getUrlInformation();
        if (isset($this->templates[$_format])) {
            return new Response($this->templating->render($this->templates[$_format], array('urls' => $urls)));
        }

        return $this->createJsonResponse($urls);
    }

    /**
     * @param array|UrlInformation[] $urls
     *
     * @return JsonResponse
     */
    private function createJsonResponse($urls)
    {
        $result = array();

        foreach ($urls as $url) {
            $result[] = $url->toArray();
        }

        return new JsonResponse($result);
    }
}
