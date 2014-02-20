<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Admin;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * This AdminExtension will serve the bundle's own form type
 * for configuring seo metadata.
 *
 * To get this admin extension you need to enable sonatas AdminBundle by
 * setting the cmf_seo.persistence.phpcr.use_sonata_admin value to true, which
 * is done for you by default. Means: you shouldn't set it to false.
 * You will need an own admin class with its mapping and let the document
 * implement the SeoAwareInterface.
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
