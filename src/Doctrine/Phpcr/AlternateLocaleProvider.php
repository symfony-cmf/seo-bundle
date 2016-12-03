<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\AlternateLocaleProviderInterface;
use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocale;
use Symfony\Cmf\Bundle\SeoBundle\Model\AlternateLocaleCollection;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * This provider looks at the actual locales of a
 * PHPCR-ODM document to determine what locales really exist.
 *
 * Note: If it would just look at the route referrers,
 * the alternates would contain the translated route for untranslated content.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class AlternateLocaleProvider implements AlternateLocaleProviderInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @param ManagerRegistry       $managerRegistry
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(ManagerRegistry $managerRegistry, UrlGeneratorInterface $urlGenerator)
    {
        $this->managerRegistry = $managerRegistry;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Creates a collection of AlternateLocales for one content object.
     *
     * @param object $content
     *
     * @return AlternateLocaleCollection
     */
    public function createForContent($content)
    {
        $alternateLocaleCollection = new AlternateLocaleCollection();
        if (!$content instanceof TranslatableInterface || !$content instanceof RouteReferrersReadInterface) {
            return $alternateLocaleCollection;
        }

        $documentManager = $this->getDocumentManagerForClass(get_class($content));
        if (null === $documentManager) {
            return $alternateLocaleCollection;
        }

        $alternateLocales = $documentManager->getLocalesFor($content);
        $currentLocale = $content->getLocale();
        foreach ($alternateLocales as $locale) {
            if ($locale === $currentLocale) {
                continue;
            }

            $alternateLocaleCollection->add(
                new AlternateLocale(
                    $this->urlGenerator->generate($content, array('_locale' => $locale), UrlGeneratorInterface::ABSOLUTE_URL),
                    $locale
                )
            );
        }

        return $alternateLocaleCollection;
    }

    /**
     * Creates a collection of AlternateLocales for many content object.
     *
     * @param array|object[] $contents
     *
     * @return AlternateLocaleCollection[]
     */
    public function createForContents(array $contents)
    {
        $result = array();
        foreach ($contents as $content) {
            $result[] = $this->createForContent($content);
        }

        return $result;
    }

    /**
     * When the registry was set, this method figure out
     * the document manager of a given class.
     *
     * @param $class
     *
     * @return DocumentManager|null|object
     */
    private function getDocumentManagerForClass($class)
    {
        return $this->managerRegistry->getManagerForClass($class);
    }
}
