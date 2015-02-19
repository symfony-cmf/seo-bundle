<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Controller;

use Symfony\Cmf\Bundle\SeoBundle\Exception\InvalidArgumentException;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\DocumentsOnSitemapProviderInterface;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationGuesserInterface;
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
     * @var DocumentsOnSitemapProviderInterface
     */
    private $documentsForSitemapProvider;
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
     * @var UrlInformationGuesserInterface
     */
    private $guesser;

    /**
     * You should provide templates for html and xml.
     *
     * Json is serialized by default, but can be customized with a template
     *
     * @param DocumentsOnSitemapProviderInterface $provider
     * @param UrlInformationGuesserInterface      $guesser
     * @param EngineInterface                     $templating
     * @param array                               $configurations List of available sitemap configurations.
     */
    public function __construct(
        DocumentsOnSitemapProviderInterface $provider,
        UrlInformationGuesserInterface $guesser,
        EngineInterface $templating,
        array $configurations
    ) {
        $this->documentsForSitemapProvider = $provider;
        $this->guesser = $guesser;
        $this->templating = $templating;
        $this->configurations = $configurations;
    }

    /**
     * @param string $_format The format of the sitemap.
     *
     * @return Response
     */
    public function indexAction($_format, $sitemap)
    {
        if (!isset($this->configurations[$sitemap])) {
            throw new InvalidArgumentException(sprintf('Unknown sitemap %s', $sitemap));
        }

        $templates = $this->configurations[$sitemap]['templates'];

        $supportedFormats = array_merge(array('json'), array_keys($templates));
        if (!in_array($_format, $supportedFormats)) {
            $text = sprintf(
                'Unknown format %s, use one of %s.',
                $_format,
                implode(', ', $supportedFormats)
            );

            return new Response($text, 406);
        }

        $urlInformations = array();
        $documents = $this->documentsForSitemapProvider->getDocumentsForSitemap($sitemap);
        foreach ($documents as $document) {
            $urlInformation = new UrlInformation();
            $this->guesser->guessValues($urlInformation, $document, $sitemap);
            $urlInformations[] = $urlInformation;
        }

        if (isset($templates[$_format])) {
            return new Response($this->templating->render($templates[$_format], array('urls' => $urlInformations)));
        }

        return $this->createJsonResponse($urlInformations);
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
