<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Admin;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * This AdminExtension will server the bundle's own form type
 * for configuring seo metadata.
 *
 * @author Maximilian Berghoff <maximilian.berghoff@gmx.de>
 */
class SeoContentAdminExtension extends AdminExtension
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_seo')
                ->add('seoMetadata', 'seo_metadata', array('label'=>false))
            ->end()
        ;
    }
}
