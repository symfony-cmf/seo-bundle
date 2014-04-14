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
use Symfony\Cmf\Bundle\SeoBundle\Model\ExtraProperty;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\SeoAwareContent;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\ContentWithExtractors;

class LoadContentData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        NodeHelper::createPath($manager->getPhpcrSession(), '/test');
        $root = $manager->find(null, '/test');

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
        $content->setName('content-arbitrary-property');
        $content->setTitle('Content arbitrary property');
        $content->setBody('Content for arbitrary properties - meta tag with property attribute.');
        $content->setParentDocument($contentRoot);

        $metadata = new SeoMetadata();
        $metadata->addExtraProperty(new ExtraProperty('og:title', 'arbitrary title', 'property'));

        $content->setSeoMetadata($metadata);

        $manager->persist($content);

        $route = new Route();
        $route->setPosition($routeRoot, 'content-arbitrary-property');
        $route->setContent($content);
        $route->setDefault('_controller', 'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Controller\TestController::indexAction');

        $manager->persist($route);

        // content for the name attribute in the meta tag
        $content = new SeoAwareContent();
        $content->setName('content-arbitrary-name');
        $content->setTitle('Content name attribute');
        $content->setBody('Content for setting a meta tag with name attribute.');
        $content->setParentDocument($contentRoot);

        $metadata = new SeoMetadata();
        $metadata->addExtraProperty(new ExtraProperty('robots', 'index, follow', 'name'));

        $content->setSeoMetadata($metadata);

        $manager->persist($content);

        $route = new Route();
        $route->setPosition($routeRoot, 'content-arbitrary-name');
        $route->setContent($content);
        $route->setDefault('_controller', 'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Controller\TestController::indexAction');

        $manager->persist($route);

        // content for the http-equiv attribute in meta tag
        $content = new SeoAwareContent();
        $content->setName('content-arbitrary-http');
        $content->setTitle('Content http-equiv attribute');
        $content->setBody('Content for setting a meta tag with http-equiv attribute.');
        $content->setParentDocument($contentRoot);

        $metadata = new SeoMetadata();
        $metadata->addExtraProperty(new ExtraProperty('Content-Type', 'text/html; charset=utf-8', 'http-equiv'));

        $content->setSeoMetadata($metadata);

        $manager->persist($content);

        $route = new Route();
        $route->setPosition($routeRoot, 'content-arbitrary-http');
        $route->setContent($content);
        $route->setDefault('_controller', 'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Controller\TestController::indexAction');

        $manager->persist($route);

        $manager->flush();
    }
}
