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
use Doctrine\ODM\PHPCR\Document\Generic;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;

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

        $contentRoot = new Generic;
        $contentRoot->setNodename('contents');
        $contentRoot->setParent($root);
        $manager->persist($contentRoot);

        $routeRoot = new Generic;
        $routeRoot->setNodename('routes');
        $routeRoot->setParent($root);
        $manager->persist($routeRoot);

        $content = new SeoAwareContent();
        $content->setName('content-1');
        $content->setTitle('Content 1');
        $content->setBody('Content 1');
        $content->setParent($contentRoot);
        $metadata = new SeoMetadata();
        $metadata->setTitle('Title content 1');
        $metadata->setMetaDescription('Description of content 1.');
        $metadata->setMetaKeywords('content1, content');
        $metadata->setOriginalUrl('/to/original');
        $content->setSeoMetadata($metadata);
        $manager->persist($content);

        /*
        $route = new Route();
        $route->setParent($routeRoot);
        $route->setContent($content);
        $route->setName('content-1');
        $manager->persist($route);
        */
        $content = new SeoAwareContent();
        $content->setName('content-2');
        $content->setTitle('Content 2');
        $content->setBody('Content 2');
        $content->setParent($contentRoot);
        $metadata->setTitle('Title content 2');
        $metadata->setMetaDescription('Description of content 2.');
        $metadata->setMetaKeywords('content2, content');
        $metadata->setOriginalUrl('/to/original2');
        $content->setSeoMetadata($metadata);
        $manager->persist($content);

        /*
        $route = new Route();
        $route->setParent($routeRoot);
        $route->setContent($content);
        $route->setName('content-2');
        $manager->persist($route);
*/
        $manager->flush();
    }
}
