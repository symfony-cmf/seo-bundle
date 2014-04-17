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

use Doctrine\Common\Collections\Collection;
use Symfony\Cmf\Bundle\SeoBundle\Model\ExtraProperty;

/**
 * This interface is one of the ExtractorInterfaces to
 * get content properties for updating the SeoMetadata.
 *
 * If you want to have a content that is able to update its extra properties
 * for the SeoMetadata on its own, you can implement this interface.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface ExtraPropertiesReadInterface
{
    /**
     * Provides a list of ExtraProperty values for this page's SEO context.
     *
     * @return Collection|ExtraProperty[] Either a Collection or a plain array
     *      of ExtraProperty instances.
     */
    public function getSeoExtraProperties();
}
