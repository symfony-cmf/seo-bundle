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

use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Templating\EngineInterface;

/**
 * Controller to handle requests for sitemap.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * The complete configurations for all sitemap with its
     * definitions for their templates.
     *
     * @var array
     */
    private $configurations;

    /**
     * @var UrlInformationProvider
     */
    private $sitemapProvider;

    /**
     * You should provide templates for html and xml.
     *
     * Json is serialized by default, but can be customized with a template
     *
     * @param UrlInformationProvider $sitemapProvider
     * @param EngineInterface        $templating
     * @param array                  $configurations  list of available sitemap configurations
     */
    public function __construct(
        UrlInformationProvider $sitemapProvider,
        EngineInterface $templating,
        array $configurations
    ) {
        $this->templating = $templating;
        $this->configurations = $configurations;
        $this->sitemapProvider = $sitemapProvider;
    }

    /**
     * @param string $_format the format of the sitemap
     * @param string $sitemap the sitemap to show
     *
     * @return Response
     */
    public function indexAction($_format, $sitemap)
    {
        if (!isset($this->configurations[$sitemap])) {
            throw new NotFoundHttpException(sprintf('Unknown sitemap "%s"', $sitemap));
        }

        $templates = $this->configurations[$sitemap]['templates'];

        $supportedFormats = array_merge(['json'], array_keys($templates));
        if (!in_array($_format, $supportedFormats)) {
            $text = sprintf(
                'Unknown format %s, use one of %s.',
                $_format,
                implode(', ', $supportedFormats)
            );

            return new Response($text, 406);
        }

        $urlInformation = $this->sitemapProvider->getUrlInformation($sitemap);

        if (isset($templates[$_format])) {
            return new Response($this->templating->render($templates[$_format], ['urls' => $urlInformation]));
        }

        return $this->createJsonResponse($urlInformation);
    }

    /**
     * @param array|UrlInformation[] $urls
     *
     * @return JsonResponse
     */
    private function createJsonResponse($urls)
    {
        $result = [];

        foreach ($urls as $url) {
            $result[] = $url->toArray();
        }

        return new JsonResponse($result);
    }
}
