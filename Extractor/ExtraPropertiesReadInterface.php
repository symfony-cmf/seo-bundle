<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Extractor;

/**
 * This interface is one of the ExtractorInterfaces to
 * get content properties for updating the SeoMetadata.
 *
 * If you want to have a content that is able to update its arbitrary
 * properties for the SeoMetadata on its own,
 * you can implement this interface. There a 3 methods for each
 * meta types property, name and http-equiv.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface ExtraPropertiesReadInterface
{
    /**
     * Provides a list of extras as key-values-pairs
     * for this page's SEO context and meta type property.
     *
     * @return array
     */
    public function getSeoExtraProperties();

    /**
     * Provides a list of extras as key-values-pairs
     * for this page's SEO context and meta type name.
     *
     * @return array
     */
    public function getSeoExtraNames();

    /**
     * Provides a list of extras as key-values-pairs
     * for this page's SEO context and meta type http-equiv.
     *
     * @return array
     */
    public function getSeoExtraHttp();
}
