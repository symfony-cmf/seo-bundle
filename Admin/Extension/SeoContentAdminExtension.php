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
use Sonata\AdminBundle\Form\FormMapper;

/**
 * This AdminExtension will serve the bundle's own form type
 * for configuring seo metadata.
 *
 * To get this admin extension, you need to enable SonataAdminBundle support by
 * setting `cmf_seo.persistence.phpcr.use_sonata_admin` in the configuration to
 * `true` (it defaults to `auto`, which means it'll use it when the
 * SonataAdminBundle is registered). You will need your own Admin class with its
 * mapping and let the content implement the SeoAwareInterface.
 *
 * @author Maximilian Berghoff <maximilian.berghoff@gmx.de>
 */
class SeoContentAdminExtension extends AdminExtension
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_seo', array('translation_domain' => 'CmfSeoBundle'))
                ->add('seoMetadata', 'seo_metadata', array('label' => false))
            ->end()
        ;
    }
}
