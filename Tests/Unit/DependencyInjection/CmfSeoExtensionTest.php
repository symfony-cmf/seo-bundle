<?php

namespace Symfony\Cmf\SeoBundle\Tests\Unit\DependencyInjection;


use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\CmfSeoExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class CmfSeoExtensionTest extends AbstractExtensionTestCase{

    /**
     * {@inheritDoc}
     */
    protected function getContainerExtensions()
    {
        return array(
            new CmfSeoExtension(),
        );
    }

    public function testDefaults()
    {
        $this->load(array(
            'title'         => 'Default title',
            'description'   => 'Default description.',
        ));

        $this->assertContainerBuilderHasParameter('cmf_seo.title', 'Default title');
        $this->assertContainerBuilderHasParameter('cmf_seo.description', 'Default description.');
        $this->assertContainerBuilderHasParameter('cmf_seo.translation_domain', 'messages');
        $this->assertContainerBuilderHasParameter('cmf_seo.original_route_pattern', 'canonical');
        $this->assertContainerBuilderHasParameter('cmf_seo.content_key', 'contentDocument');
    }

    public function testPersistencePHPCR()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
            )
        );
        $this->load(array(
            'title'         => 'Default title',
            'description'   => 'Default description.',
            'persistence'   => array(
                'phpcr' => true,
            )
        ));

        $this->assertContainerBuilderHasService('cmf_seo.persistence.metadata_listener', '%cmf_seo.persistence.metadata_listener.class%');

        $this->assertEquals(
            array('doctrine_phpcr.event_subscriber'),
            array_keys(
                $this->container->getDefinition('cmf_seo.persistence.metadata_listener')->getTags()
            )
        );
    }

    public function testPersistenceORM()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
                'SonataDoctrineORMBundle' => true,
            )
        );

        $this->load(array(
            'title'         => 'Default title',
            'description'   => 'Default description.',
            'persistence'   => array(
                'orm'   => true,
            ),
        ));

        $this->assertContainerBuilderHasService('cmf_seo.persistence.metadata_listener', '%cmf_seo.persistence.metadata_listener.class%');
        $this->assertEquals(
            array('orm.event_subscriber'),
            array_keys(
                $this->container->getDefinition('cmf_seo.persistence.metadata_listener')->getTags()
            )
        );
    }

    public function testAdminExtension()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
                'SonataDoctrineORMBundle' => true,
            )
        );

        $this->load(array(
            'title'                     => 'Default title',
            'description'               => 'Default description.',
            'sonata_admin_extension'    => true,
            'persistence'               => array(
                'phpcr' => true,
            ),
        ));

        $this->assertContainerBuilderHasService('cmf_seo.admin_extension', '%cmf_seo.admin_extension.class%');
    }
}
