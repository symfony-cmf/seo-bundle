<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Admin;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Form\FormMapper;

class SeoContentAdminExtension extends AdminExtesion
{

    protected $translationDomain = 'SeoBundle';

    protected $baseRouteName = 'cmf_seo';

    protected $baseRoutePattern = 'seo_content';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_seo')
                ->add('seoMetadata', 'seo_metadata', array('label'=>false))
            ->end()
        ;
    }
}
