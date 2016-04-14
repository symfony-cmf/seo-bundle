<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocaleCollection;

/**
 * An interface for providing alternate locale urls.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
interface AlternateLocaleProviderInterface
{
    /**
     * Creates a collection of AlternateLocales for one content object.
     *
     * @param object $content
     *
     * @return AlternateLocaleCollection
     */
    public function createForContent($content);

    /**
     * Creates a collection of AlternateLocales for many content object.
     *
     * @param array|object[] $contents
     *
     * @return AlternateLocaleCollection[]
     */
    public function createForContents(array $contents);
}
