<?php

namespace Symfony\Cmf\SeoBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\CmfSeoExtension;
use Symfony\Component\DependencyInjection\Definition;

class CmfSeoExtensionTest extends AbstractExtensionTestCase
{
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
                'DoctrinePHPCRBundle' => true,
                'BurgovKeyValueFormBundle' => true,
            )
        );
        $this->load(array(
            'title'         => 'Default title',
            'description'   => 'Default description.',
            'persistence'   => array(
                'phpcr' => true,
            )
        ));
    }

    public function testPersistenceORM()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
                'SonataDoctrineORMBundle' => true,
                'BurgovKeyValueFormBundle' => true,
            )
        );

        $this->load(array(
            'title'         => 'Default title',
            'description'   => 'Default description.',
            'persistence'   => array(
                'orm'   => true,
            ),
        ));
    }

    public function testAdminExtension()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
                'SonataDoctrineORMBundle' => true,
                'DoctrinePHPCRBundle' => true,
            )
        );

        $this->load(array(
            'title'                  => 'Default title',
            'description'            => 'Default description.',
            'sonata_admin_extension' => true,
            'persistence'            => array(
                'phpcr' => true,
            ),
        ));

        $this->assertContainerBuilderHasService('cmf_seo.admin_extension', 'Symfony\Cmf\Bundle\SeoBundle\Admin\Extension\SeoContentAdminExtension');
    }

    public function testAlternateLocaleWithPhpcr()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'DoctrinePHPCRBundle' => true,
                'CmfRoutingBundle' => true,
            )
        );
        $this->load(array(
            'persistence'   => array(
                'phpcr' => true,
            ),
            'alternate_locale' => array(
                'enabled' => true
            ),
        ));

        $this->assertContainerBuilderHasService(
            'cmf_seo.alternate_locale.provider_phpcr',
            'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\AlternateLocaleProvider'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.event_listener.seo_content',
            'setAlternateLocaleProvider',
            array($this->container->getDefinition('cmf_seo.alternate_locale.provider_phpcr'))
        );
    }

    public function testAlternateLocaleWithCustomProvider()
    {
        $this->container->setDefinition('some_alternate_locale_provider', new Definition());
        $this->load(array(
            'alternate_locale' => array(
                'provider_id' => 'some_alternate_locale_provider'
            ),
        ));

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.event_listener.seo_content',
            'setAlternateLocaleProvider',
            array($this->container->getDefinition('some_alternate_locale_provider'))
        );
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testExceptionForNonExistingBurgovBundleWithAdminExtension()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
            )
        );
        $this->load(array(
            'persistence'   => array(
                'phpcr' => true,
            )
        ));
    }
}
