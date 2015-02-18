<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\Query\QueryException;
use Jackalope\Transport\Logging\LoggerInterface;
use PHPCR\Query\QueryInterface;
use Psr\Log\LoggerInterface as PsrLogger;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishWorkflowChecker;
use Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\ExtractorInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\UrlInformation;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProviderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Provides UrlInformation for pages in a Doctrine PHPCR ODM back-end.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapUrlInformationProvider implements UrlInformationProviderInterface
{
    /**
     * @var AlternateLocaleProviderInterface
     */
    protected $alternateLocaleProvider;

    /**
     * @var ExtractorInterface
     */
    protected $titleExtractor;

    /**
     * @var DocumentManager
     */
    private $manager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SecurityContextInterface
     */
    private $publishWorkflowChecker;
    /**
     * @var string
     */
    private $defaultChanFrequency;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var SeoPresentation
     */
    private $seoPresentation;

    /**
     * @param DocumentManager $manager
     * @param RouterInterface $router
     * @param string $defaultChanFrequency
     * @param PsrLogger $logger
     * @param SeoPresentation $seoPresentation
     * @param SecurityContextInterface $publishWorkflowChecker
     */
    public function __construct(
        DocumentManager $manager,
        RouterInterface $router,
        $defaultChanFrequency,
        PsrLogger $logger,
        SeoPresentation $seoPresentation,
        SecurityContextInterface $publishWorkflowChecker = null
    ) {
        $this->manager = $manager;
        $this->router = $router;
        $this->defaultChanFrequency = $defaultChanFrequency;
        $this->logger = $logger;
        $this->seoPresentation = $seoPresentation;
        $this->publishWorkflowChecker = $publishWorkflowChecker;
    }

    /**
     * {@inheritDocs}
     */
    public function getUrlInformation()
    {
        $contentDocuments = $this->fetchSitemapDocuments();
        $urlInformationList = array();

        foreach ($contentDocuments as $document) {
            if (null != $this->publishWorkflowChecker &&
                !$this->publishWorkflowChecker->isGranted(array(PublishWorkflowChecker::VIEW_ATTRIBUTE), $document)
            ) {
                continue;
            }

            try {
                $urlInformationList[] = $this->computeUrlInformationFromSitemapDocument($document);
            } catch (\Exception $e) {
                $this->logger->info($e->getMessage());
            }
        }

        return $urlInformationList;
    }

    /**
     * @param AlternateLocaleProviderInterface $alternateLocaleProvider
     */
    public function setAlternateLocaleProvider(AlternateLocaleProviderInterface $alternateLocaleProvider)
    {
        $this->alternateLocaleProvider = $alternateLocaleProvider;
    }

    /**
     * Wrapper to fetch the sitemap documents from database.
     *
     * @return object[]
     *
     * @throws QueryException
     */
    protected function fetchSitemapDocuments()
    {
        // todo rewrite query when https://github.com/symfony-cmf/CoreBundle/issues/126 is ready
        return $this->manager->createQuery(
            "SELECT * FROM [nt:unstructured] WHERE (visible_for_sitemap = true)",
            QueryInterface::JCR_SQL2
        )->execute();
    }

    /**
     * Transforms a single sitemap document into url information.
     *
     * A sitemap document is a document, which should be exposed on a sitemap.
     *
     * @param object $document
     *
     * @return UrlInformation
     */
    protected function computeUrlInformationFromSitemapDocument($document)
    {
        $urlInformation = new UrlInformation();
        $urlInformation->setLocation($this->router->generate($document, array(), true));
        $urlInformation->setChangeFrequency($this->defaultChanFrequency);

        if ($this->alternateLocaleProvider) {
            $collection = $this->alternateLocaleProvider->createForContent($document);
            $urlInformation->setAlternateLocales($collection->toArray());
        }

        $seoMetadata = $this->seoPresentation->getSeoMetadata($document);
        if (null !== $seoMetadata->getTitle()) {
            $urlInformation->setLabel($seoMetadata->getTitle());
            return $urlInformation;
        }
        return $urlInformation;
    }
}
