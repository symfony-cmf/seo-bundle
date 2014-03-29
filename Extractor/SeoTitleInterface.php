<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

/**
 * This interface is one of the ExtractorInterfaces to get a documents property
 * for updating the SeoMetadata.
 *
 * If you want to have a document that is able to update its title for the
 * SeoMetadata on its own, you should implement this interface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface SeoTitleInterface
{
    /**
     * Provide a title of this page to be used in SEO context.
     *
     * @return string
     */
    public function getSeoTitle();
}
