<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use PHPCR\Util\UUIDHelper;
use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoAwareException;
use Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoExtractorStrategyException;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoExtractorStrategyInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalRouteExtractorStrategy;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * This presentation model prepares the data for the SeoPage service of the
 * SonataSeoBundle, which is able to provide the values to its Twig helpers.
 *
 * Preparing means combining the title value of the SeoMetadata and the default
 * value defined in the cmf_seo.title.default parameter. Both strings are
 * concatenated by an separator depending on the strategy set in the config.
 *
 * The content config under cmf_seo.content gives a strategy how to handle duplicate
 * content. If it is set to canonical a canonical link is created by an Twig helper
 * (url must be set to the SeoPage), otherwise the url is set to the redirectResponse property
 * which triggers an redirectResponse.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoPresentation extends AbstractSeoPresentation
{
    /**
     * @var SeoPage
     */
    protected $sonataPage;

    /**
     * @var SeoMetadataInterface
     */
    protected $seoMetadata;

    /**
     * @var SeoExtractorStrategyInterface[]
     */
    protected $strategies = array();

    /**
     * The constructor will set the injected SeoPage - the service of
     * sonata which is responsible for storing the seo data.
     *
     * @param SeoPage $sonataPage
     * @param array $strategies
     * @throws \Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoExtractorStrategyException
     */
    public function __construct(SeoPage $sonataPage, array $strategies)
    {
        $this->sonataPage = $sonataPage;

        foreach ($strategies as $strategy) {
            if (!$strategy instanceof SeoExtractorStrategyInterface) {
                throw new SeoExtractorStrategyException('Wrong Strategy given.');
            }
            array_push($this->strategies, $strategy);
        }
    }

    /**
     * This method is used to get the SeoMetadata from current content document.
     *
     * @throws \Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoExtractorStrategyException
     * @return SeoMetadata
     */
    protected function getSeoMetadata()
    {

        $seoMetadata = $this->contentDocument instanceof SeoAwareInterface
                        ? (clone $this->contentDocument->getSeoMetadata())
                        : new SeoMetadata()
        ;

        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($this->contentDocument)) {
                $strategy->updateMetadata($this->contentDocument, $seoMetadata);
            }
        }

        return $seoMetadata;
    }

    /**
     *  this method will combine all settings directly in the sonata_seo configuration with
     *  the given values of the current content
     */
    public function setMetaDataValues()
    {
        //get the current seo metadata out of the document
        $this->seoMetadata = $this->getSeoMetadata();

        //based on the title strategy, the helper method will set the complete title
        if (null !== $this->seoMetadata->getTitle()) {
            $title = $this->createTitle();

            //set the title to SeoPage and  a meta field
            $this->sonataPage->setTitle($title);
            $this->sonataPage->addMeta('names', 'title', $title);
        }

        if (null !== $this->seoMetadata->getMetaDescription()) {
            $this->sonataPage->addMeta(
                'names',
                'description',
                $this->createDescription()
            );
        }

        if (null !== $this->seoMetadata->getMetaKeywords()) {
            $this->sonataPage->addMeta(
                'names',
                'keywords',
                $this->createKeywords()
            );
        }

        //if the strategy for duplicate content is canonical, the service will trigger an canonical link
        switch ($this->contentParameters['strategy']) {
            case 'canonical':
                $this->sonataPage->setLinkCanonical($this->seoMetadata->getOriginalUrl());
                break;
            case 'redirect':
                $this->setRedirectResponse(
                    $this->createRedirectUrl($this->seoMetadata->getOriginalUrl())
                );
                break;
        }
    }

    /**
     * Based on the title strategy this method will create the title from the given
     * configs in the seo configuration part.
     *
     * @return string
     */
    private function createTitle()
    {
        $defaultTitle = $this->doMultilangDecision($this->titleParameters['default']);
        $separator = $this->titleParameters['separator'];
        $contentTitle = $this->seoMetadata->getTitle();

        if ('' == $defaultTitle) {
            return $contentTitle;
        }

        switch ($this->titleParameters['strategy']) {
            case 'prepend':
                return $contentTitle.$separator.$defaultTitle;
            case 'append':
                return $defaultTitle .$separator. $contentTitle;
            case 'replace':
                return $contentTitle;
            default:
                return $defaultTitle;
        }
    }

    /**
     * Depending on the current locale and the setting for the default title this
     * method will return the default title as a string.
     *
     * @param  array|string                                               $defaultTitle
     * @throws \Symfony\Cmf\Bundle\SeoBundle\Exceptions\SeoAwareException
     * @return array|string
     */
    private function doMultilangDecision($defaultTitle)
    {
        if (is_string($defaultTitle)) {
            return $defaultTitle;
        }

        // try the current location of the document, seoMetadata should have the same
        $currentLocale = $this->getModelLocale();
        if (is_array($defaultTitle) && isset($defaultTitle[$currentLocale])) {
            return $defaultTitle[$currentLocale];
        }

        //try the applications default locale
        $defaultLocale = $this->getApplicationDefaultLocale();
        if (is_array($defaultTitle) && isset($defaultTitle[$defaultLocale])) {
            return $defaultTitle[$defaultLocale];
        }

        throw new SeoAwareException(
            sprintf(
                'No default value of title found for current document locale %s and applications default %s',
                $currentLocale,
                $defaultLocale
            )
        );
    }

    /**
     * As you can set your default description in the sonata_seo settings and
     * can add some more from your contend, this method will combine both.
     *
     * @return string
     */
    private function createDescription()
    {
        $sonataDescription = isset($this->sonataPage->getMetas()['names']['description'][0])
                                ? $this->sonataPage->getMetas()['names']['description'][0]
                                : '';

        return ('' !== $sonataDescription ? $sonataDescription.'. ' : '').$this->seoMetadata->getMetaDescription();
    }

    /**
     * Same as for the previous method. You can set the keywords in your sonata seo
     * setting, but each SeoAwareContent is able to set its own, this method will combine
     * both.
     *
     * @return string
     */
    private function createKeywords()
    {
        $sonataKeywords = isset($this->sonataPage->getMetas()['names']['keywords'][0])
                                ? $this->sonataPage->getMetas()['names']['keywords'][0]
                                : '';

        return ('' !== $sonataKeywords ? $sonataKeywords.', ' : '').$this->seoMetadata->getMetaKeywords();
    }

    /**
     * As there are several ways to set the original route for a content,
     * there are several solutions needed to create a path for the redirectResponse route
     * out of it.
     */
    private function createRedirectUrl($value)
    {
        $routeStrategy = new SeoOriginalRouteExtractorStrategy();

        if (is_string($value) && !UUIDHelper::isUUID($value)) {
            //The value is just a plain url
            return new RedirectResponse($value);
        }

        $routeDocument = null;
        if ($routeStrategy->supports($this->contentDocument)) {
            //than the value is a route document
            $routeDocument = $value;
        }

        if (is_string($value) && UUIDHelper::isUUID($value)) {
            //the value is the uuid of a route document, one of the documents routes was selected
            $routeDocument = $this->getDocumentManager()->find(null, $value);
        }

        if (!$routeDocument instanceof Route) {
            throw new SeoAwareException('No redirect route found.');
        }

        return new RedirectResponse($this->router->generate($routeDocument));
    }
}
