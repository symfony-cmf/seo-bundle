<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

/**
 * This interface is one of the ExtractorInterfaces to
 * get document properties for updating the SeoMetadata.
 *
 * If you want to have a document that is able to update its
 * keywords for the SeoMetadata on its own, you should implement
 * this interface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface KeywordsReadInterface
{
    /**
     * Provides a list of keywords for this page to be
     * used in SEO context.
     *
     * @return string|array
     */
    public function getSeoKeywords();
}
