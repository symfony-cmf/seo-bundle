<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Query\QueryInterface;
use Symfony\Cmf\Bundle\SeoBundle\Sitemap\LoaderInterface;

/**
 * Provides documents for a sitemap from a phpcr backend.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SitemapDocumentProvider implements LoaderInterface
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
     * {@inheritdoc}.
     */
    public function load($sitemap)
    {
        // todo rewrite query when https://github.com/symfony-cmf/CoreBundle/issues/126 is ready
        $documentsCollection = $this->manager->createQuery(
            'SELECT * FROM [nt:unstructured] WHERE (visible_for_sitemap = true)',
            QueryInterface::JCR_SQL2
        )->execute();

        $documents = array();
        // the chain provider does not like collections as we array_merge in there
        foreach ($documentsCollection as $document) {
            $documents[] = $document;
        }

        return $documents;
    }
}
