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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\SeoBundle\Model\ExtraProperty;

/**
 * This interface is one of the ExtractorInterfaces to
 * get content properties for updating the SeoMetadata.
 *
 * If you want to have a content that is able to update its
 * arbitrary properties for the SeoMetadata on its own, you should implement
 * this interface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface ExtraPropertiesReadInterface
{
    /**
     * Provides a collection of arbitrary properties of this page's SEO context.
     *
     * @return ArrayCollection|ExtraProperty[]
     */
    public function getSeoExtraProperties();
}
