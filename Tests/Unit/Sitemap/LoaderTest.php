<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\Sitemap;

use Symfony\Cmf\Bundle\SeoBundle\Sitemap\Loader;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class LoaderTest extends AbstractChainTest
{

    protected function getChain()
    {
        return new Loader();
    }

    protected function getInterface()
    {
        return '\Symfony\Cmf\Bundle\SeoBundle\Sitemap\LoaderInterface';
    }

    protected function getMethodName()
    {
        return 'load';
    }

    protected function getParameter()
    {
        return array('test');
    }
}
