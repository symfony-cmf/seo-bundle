<?php

namespace Cmf\SeoBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;

class SeoAwareContentAdminController extends Admin{

    protected $translationDomain = 'CmfSeoBundle';

    protected $baseRouteName = 'cmf_seo';

    protected $baseRoutePattern = 'seo_content';

    public function getNewInstance()
    {
        $new = parent::getNewInstance();
        if ($this->hasRequest()) {
            $parentId = $this->getRequest()->query->get('parent');
            if (null !== $parentId) {
                $new->setParent($this->getModelManager()->find(null, $parentId));
            }
        }

        return $new;
    }

    public function getExportFormats()
    {
        return array();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', 'text')
            ->addIdentifier('title', 'text')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_general')
                ->add('parent', 'doctrine_phpcr_odm_tree', array('root_node' => $this->getRootPath(), 'choice_list' => array(), 'select_root_node' => true))
                ->add('name', 'text')
                ->add('title', 'text')
                ->add('body', 'textarea', array('required' => false))
            ->with('form.group_seo')
                ->add('seoStuff', 'seo_stuff')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', 'doctrine_phpcr_string')
            ->add('name',  'doctrine_phpcr_nodename')
        ;
    }

    public function toString($object)
    {
        return $object instanceof StaticContentBase && $object->getTitle()
            ? $object->getTitle()
            : $this->trans('link_add', array(), 'SonataAdminBundle')
            ;
    }
} 