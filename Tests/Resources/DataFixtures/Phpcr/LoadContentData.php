<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SeoAwareContent;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\ContentWithExtractors;

class LoadContentData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        NodeHelper::createPath($manager->getPhpcrSession(), '/test');

        NodeHelper::createPath($manager->getPhpcrSession(), '/test/content');
        NodeHelper::createPath($manager->getPhpcrSession(), '/test/routes/content');

        $contentRoot = $manager->find(null, '/test/content');
        $routeRoot = $manager->find(null, '/test/routes/content');

        $content = new SeoAwareContent();
        $content->setName('content-1');
        $content->setTitle('Content 1');
        $content->setBody('Content 1');
        $content->setParentDocument($contentRoot);

        $metadata = new SeoMetadata();
        $metadata->setTitle('Title content 1');
        $metadata->setMetaDescription('Description of content 1.');
        $metadata->setMetaKeywords('content1, content');
        $metadata->setOriginalUrl('/to/original');

        $content->setSeoMetadata($metadata);
        $manager->persist($content);

        $route = new Route();
        $route->setPosition($routeRoot, 'content-1');
        $route->setContent($content);
        $route->setDefault('_controller', 'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Controller\TestController::indexAction');

        $manager->persist($route);

        $content = new ContentWithExtractors();
        $content->setName('strategy-title');
        $content->setTitle('Strategy title');
        $content->setBody('content of strategy test.');
        $content->setParentDocument($contentRoot);

        $manager->persist($content);

        $route = new Route();
        $route->setPosition($routeRoot, 'strategy-content');
        $route->setContent($content);
        $route->setDefault('_controller', 'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Controller\TestController::indexAction');

        $manager->persist($route);

        $content = new SeoAwareContent();
        $content->setName('content-extra');
        $content->setTitle('Content extra');
        $content->setBody('Content for extra properties.');
        $content->setParentDocument($contentRoot);

        $metadata = new SeoMetadata();
        $metadata->addExtraProperty('og:title', 'extra title');
        $metadata->addExtraName('robots', 'index, follow');
        $metadata->addExtraHttp('Content-Type', 'text/html; charset=utf-8');

        $content->setSeoMetadata($metadata);
        $manager->persist($content);

        $route = new Route();
        $route->setPosition($routeRoot, 'content-extra');
        $route->setContent($content);
        $route->setDefault('_controller', 'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Controller\TestController::indexAction');

        $manager->persist($route);

        $manager->flush();
    }
}
