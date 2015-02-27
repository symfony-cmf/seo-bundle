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
use PHPCR\Query\QueryInterface;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\Provider\ContentOnSitemapProviderInterface;

/**
 * Provides UrlInformation for pages in a Doctrine PHPCR ODM back-end.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapDocumentProvider implements ContentOnSitemapProviderInterface
{
    /**
     * @var DocumentManager
     */
    private $manager;

    /**
     * @param DocumentManager $manager
     */
    public function __construct(DocumentManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritDocs}
     */
    public function getDocumentsForSitemap($sitemap)
    {
        /* todo use for voter later
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
        */

        // todo rewrite query when https://github.com/symfony-cmf/CoreBundle/issues/126 is ready
        $documentsCollection = $this->manager->createQuery(
            "SELECT * FROM [nt:unstructured] WHERE (visible_for_sitemap = true)",
            QueryInterface::JCR_SQL2
        )->execute();

        $documents = array();
        // the chain provider does not like collections as we array_merge in there
        foreach ($documentsCollection as $document) {
            $documents[] = $document;
        };
        return $documents;
    }
}
