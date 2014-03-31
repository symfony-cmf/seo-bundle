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
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata;

/**
 * Serializes the SeoMetadata of a Document, so it can savely be saved.
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
        $document = $args->getObject();
        if (!$document instanceof SeoAwareInterface) {
            return;
        }

        $document->setSeoMetadata($document->getSeoMetadata()->toArray());
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->preUpdate($args);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $document = $args->getObject();
        if (!$document instanceof SeoAwareInterface) {
            return;
        }

        $document->setSeoMetadata(SeoMetadata::createFromArray($document->getSeoMetadata()));
    }
}
