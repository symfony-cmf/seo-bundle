<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;

/**
 * This AdminExtension will serve the bundle's own form type
 * for configuring seo metadata.
 *
 * @author Maximilian Berghoff <maximilian.berghoff@gmx.de>
 */
class SeoContentAdminExtension extends AdminExtension
{
    /**
     * @var string
     */
    protected $formGroup;

    /**
     * @param string $formGroup group name to use for form mapper
     */
    public function __construct($formGroup = 'form.group_seo')
    {
        $this->formGroup = $formGroup;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with($this->formGroup, array(
                'translation_domain' => 'CmfSeoBundle',
            ))
                ->add('seoMetadata', 'seo_metadata', array('label' => false))
            ->end()
        ;
    }

    public function preUpdate(AdminInterface $admin, $seoAware)
    {
        $this->propagateLocale($seoAware);
    }

    public function prePersist(AdminInterface $admin, $seoAware)
    {
        $this->propagateLocale($seoAware);
    }

    /**
     * The seo metadata that was edited embedded has the same locale as the
     * containing document.
     *
     * @param SeoAwareInterface $seoAware
     */
    private function propagateLocale(SeoAwareInterface $seoAware)
    {
        if (!$seoAware instanceof TranslatableInterface) {
            return;
        }
        $seoMetadata = $seoAware->getSeoMetadata();
        if (!$seoMetadata instanceof TranslatableInterface) {
            return;
        }
        $seoMetadata->setLocale($seoAware->getLocale());
    }
}
