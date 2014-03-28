<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2013 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\DataFixtures\Phpcr;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Document\AllStrategiesDocument;

class LoadContentData implements FixtureInterface, DependentFixtureInterface
{
    public function getDependencies()
    {
        return array(
            'Symfony\Cmf\Component\Testing\DataFixtures\PHPCR\LoadBaseData',
        );
    }

    public function load(ObjectManager $manager)
    {
        $root = $manager->find(null, '/test');

        NodeHelper::createPath($manager->getPhpcrSession(), '/test/content');
        NodeHelper::createPath($manager->getPhpcrSession(), '/test/routes/content');

        $contentRoot = $manager->find(null, '/test/content');
        $routeRoot = $manager->find(null, '/test/routes/content');

        $content = new SeoAwareContent();
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
        unset($content, $route);

        $strategyContent = new SeoAwareContent();
        $strategyContent->setTitle('Strategy title');
        $strategyContent->setBody('content of strategy test.');
        $strategyContent->setParentDocument($contentRoot);
        //insert empty meta data
        $strategyMetadata = new SeoMetadata();
        $strategyMetadata->setTitle('');
        $strategyMetadata->setOriginalUrl('');
        $strategyMetadata->setMetaDescription('');
        $strategyMetadata->setMetaKeywords('strategy, test');
        $strategyContent->setSeoMetadata($strategyMetadata);
        $manager->persist($strategyContent);

        $strategyRoute = new Route();
        $strategyRoute->setPosition($routeRoot, 'strategy-content');
        $strategyRoute->setContent($strategyContent);
        $strategyRoute->setDefault('_controller', 'Symfony\Cmf\Bundle\SeoBundle\Tests\Resources\Controller\TestController::indexAction');
        $manager->persist($strategyRoute);

        $manager->flush();
    }
}
