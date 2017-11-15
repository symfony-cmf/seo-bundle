<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Fixtures\App\DataFixtures\Phpcr;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Fixtures\App\Document\SitemapAwareContent;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Fixtures\App\Document\SitemapAwareWithLastModifiedDateContent;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Fixtures\App\Document\SitemapAwareWithPublishWorkflowContent;

class LoadSitemapData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        NodeHelper::createPath($manager->getPhpcrSession(), '/test');

        NodeHelper::createPath($manager->getPhpcrSession(), '/test/content');
        NodeHelper::createPath($manager->getPhpcrSession(), '/test/routes');

        $contentRoot = $manager->find(null, '/test/content');
        $routeRoot = $manager->find(null, '/test/routes');

        $sitemapAwareContent = new SitemapAwareContent();
        $sitemapAwareContent
            ->setIsVisibleForSitemap(true)
            ->setTitle('Sitemap Aware Content')
            ->setName('sitemap-aware')
            ->setParentDocument($contentRoot)
            ->setBody('Content for that is sitemap aware');
        $manager->persist($sitemapAwareContent);
        $manager->bindTranslation($sitemapAwareContent, 'en');
        $sitemapAwareContent
            ->setTitle('Content für die Sitemap')
            ->setBody('Das sollte die Deutsche Version des Contents sein.');
        $manager->bindTranslation($sitemapAwareContent, 'de');

        $route = new Route();
        $route->setPosition($routeRoot, 'sitemap-aware');
        $route->setContent($sitemapAwareContent);
        $manager->persist($route);

        $nonPublishedContent = new SitemapAwareWithPublishWorkflowContent();
        $nonPublishedContent->setIsVisibleForSitemap(true);
        $nonPublishedContent->setPublishable(false);
        $nonPublishedContent
            ->setTitle('Sitemap Aware Content non publish')
            ->setName('sitemap-aware-non-publish')
            ->setParentDocument($contentRoot)
            ->setBody('Content for that is sitemap aware, that is not publish');
        $manager->persist($nonPublishedContent);

        $route = new Route();
        $route->setPosition($routeRoot, 'sitemap-aware-non-publish');
        $route->setContent($nonPublishedContent);
        $manager->persist($route);

        $publishedContent = new SitemapAwareWithPublishWorkflowContent();
        $publishedContent->setIsVisibleForSitemap(true);
        $publishedContent->setPublishable(true);
        $publishedContent
            ->setTitle('Sitemap Aware Content publish')
            ->setName('sitemap-aware-publish')
            ->setParentDocument($contentRoot)
            ->setBody('Content for that is sitemap aware, that is publish');
        $manager->persist($publishedContent);

        $route = new Route();
        $route->setPosition($routeRoot, 'sitemap-aware-publish');
        $route->setContent($publishedContent);
        $manager->persist($route);

        $lastModifiedContent = new SitemapAwareWithLastModifiedDateContent();
        $lastModifiedContent
            ->setIsVisibleForSitemap(true)
            ->setLastModified(new \DateTime('2016-07-06', new \DateTimeZone('Europe/Berlin')))
            ->setTitle('Sitemap Aware Content last mod date')
            ->setName('sitemap-aware-last-mod-date')
            ->setParentDocument($contentRoot)
            ->setBody('Content for that is sitemap aware, that has last modified date.');
        $manager->persist($lastModifiedContent);

        $route = new Route();
        $route->setPosition($routeRoot, 'sitemap-aware-last-mod-date');
        $route->setContent($lastModifiedContent);
        $manager->persist($route);

        $manager->flush();
    }
}
