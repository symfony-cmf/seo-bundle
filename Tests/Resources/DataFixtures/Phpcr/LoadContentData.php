<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2013 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ContentBundle\Tests\Resources\DataFixtures\Phpcr;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ODM\PHPCR\Document\Generic;
use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent;

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

        $content = new StaticContent;
        $content->setName('content-1');
        $content->setTitle('Content 1');
        $content->setBody('Content 1');
        $content->setParent($contentRoot);
        $manager->persist($content);

        $content = new StaticContent;
        $content->setName('content-2');
        $content->setTitle('Content 2');
        $content->setBody('Content 2');
        $content->setParent($contentRoot);
        $manager->persist($content);

        $manager->flush();
    }
}
