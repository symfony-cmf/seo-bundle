<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoMetadata;

/**
 * Serializes the SeoMetadata of the seo aware content, so it can savely be saved.
 *
 * @author Wouter J <wouter@wouterj.nl>
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class SeoMetadataListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'preUpdate',
            'prePersist',
            'postLoad'
        );
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $content = $args->getObject();
        if (!$content instanceof SeoAwareInterface) {
            return;
        }

        $content->setSeoMetadata($content->getSeoMetadata()->toArray());
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->preUpdate($args);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $content = $args->getObject();
        if (!$content instanceof SeoAwareInterface) {
            return;
        }

        $content->setSeoMetadata(SeoMetadata::createFromArray($content->getSeoMetadata()));
    }
}
