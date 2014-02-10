<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Cmf\Bundle\ContentBundle\Admin\StaticContentAdmin;

class SeoAwareContentAdminController extends StaticContentAdmin
{

    protected $translationDomain = 'CmfSeoBundle';

    protected $baseRouteName = 'cmf_seo';

    protected $baseRoutePattern = 'seo_content';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_general')
                ->add(
                    'parent',
                    'doctrine_phpcr_odm_tree',
                    array(
                        'root_node' => $this->getRootPath(),
                        'choice_list' => array(),
                        'select_root_node' => true
                        )
                )
                ->add('name', 'text')
                ->add('title', 'text')
                ->add('body', 'textarea', array('required' => false))
            ->with('form.group_seo')
                ->add('seoMetadata', 'seo_metadata', array('label'=>false))
            ->end()
        ;
    }
}
