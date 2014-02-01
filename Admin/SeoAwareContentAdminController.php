<?php

namespace Cmf\SeoBundle\Admin;

use Cmf\SeoBundle\Doctrine\Phpcr\SeoAwareContent;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SeoAwareContentAdminController extends Admin{

    protected $translationDomain = 'CmfSeoBundle';

    protected $baseRouteName = 'cmf_seo';

    protected $baseRoutePattern = 'seo_content';

    public function getNewInstance()
    {
        /** @var $new SeoAwareContent */
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

    /**
     * @param mixed $contentDocument
     * @return mixed|void
     * @todo set this stuff to the document and ad a route child list in there
     */
    public function prePersist($contentDocument)
    {
        $route = new Route();
        $route->setParent($this->getModelManager()->find(null, '/cms/routes'));
        $route->setName($contentDocument->getTitle());
        $route->setContent($contentDocument);
        $this->getModelManager()->create($route);
    }

    public function toString($object)
    {
        return $object instanceof StaticContentBase && $object->getTitle()
            ? $object->getTitle()
            : $this->trans('link_add', array(), 'SonataAdminBundle')
            ;
    }
} 