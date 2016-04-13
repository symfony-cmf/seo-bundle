<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\SeoBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\CmfSeoExtension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class CmfSeoExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
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
            'title' => 'Default title',
            'description' => 'Default description.',
        ));

        $this->assertContainerBuilderHasParameter('cmf_seo.title', 'Default title');
        $this->assertContainerBuilderHasParameter('cmf_seo.description', 'Default description.');
        $this->assertContainerBuilderHasParameter('cmf_seo.translation_domain', 'messages');
        $this->assertContainerBuilderHasParameter('cmf_seo.original_route_pattern', 'canonical');
        $this->assertContainerBuilderHasParameter('cmf_seo.content_key', 'contentDocument');
        $this->assertContainerBuilderHasService('cmf_seo.error.suggestion_provider.controller');
        $this->assertContainerBuilderNotHasService('cmf_seo.error.suggestion_provider.parent');
        $this->assertContainerBuilderNotHasService('cmf_seo.error.suggestion_provider.sibling');
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
            'title' => 'Default title',
            'description' => 'Default description.',
            'persistence' => array(
                'phpcr' => true,
            ),
            'sitemap' => true,
        ));

        $this->assertContainerBuilderHasService(
            'cmf_seo.sitemap.phpcr_loader',
            'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SitemapDocumentProvider'
        );
        $this->assertContainerBuilderHasParameter(
            'cmf_seo.form.data_class.seo_metadata',
            'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata'
        );
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
            'title' => 'Default title',
            'description' => 'Default description.',
            'persistence' => array(
                'orm' => true,
            ),
        ));

        $this->assertContainerBuilderHasParameter(
            'cmf_seo.form.data_class.seo_metadata',
            'Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata'
        );
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
            'title' => 'Default title',
            'description' => 'Default description.',
            'sonata_admin_extension' => true,
            'persistence' => array(
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
            'persistence' => array(
                'phpcr' => true,
            ),
            'alternate_locale' => array(
                'enabled' => true,
            ),
        ));

        $this->assertContainerBuilderHasService(
            'cmf_seo.alternate_locale.provider_phpcr',
            'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\AlternateLocaleProvider'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.event_listener.seo_content',
            'setAlternateLocaleProvider',
            array(new Reference('cmf_seo.alternate_locale.provider_phpcr'))
        );
    }

    public function testAlternateLocaleWithCustomProvider()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array()
        );
        $this->container->setDefinition('some_alternate_locale_provider', new Definition());
        $this->load(array(
            'persistence' => array(
                'phpcr' => true,
            ),
            'alternate_locale' => array(
                'provider_id' => 'some_alternate_locale_provider',
            ),
            'sitemap' => true,
        ));

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.event_listener.seo_content',
            'setAlternateLocaleProvider',
            array(new Reference(('some_alternate_locale_provider')))
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
            'persistence' => array(
                'phpcr' => true,
            ),
        ));
    }

    public function testErrorHandlingPHPCR()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
            )
        );

        $exclusionRules = array(
            array(
                'path' => 'some/path',
                'host' => 'test.de',
                'methods' => 'GET',
                'ips' => 'IP',
            ),
            array(
                'path' => 'some-other/path',
                'host' => 'test-dev.de',
                'methods' => 'POST',
                'ips' => 'IPs',
            ),
        );

        $this->load(array(
            'persistence' => array(
                'phpcr' => true,
            ),
            'error' => array(
                'enable_parent_provider' => true,
                'enable_sibling_provider' => true,
                'exclusion_rules' => $exclusionRules,
            ),
        ));

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'cmf_seo.error.suggestion_provider.sibling',
            'cmf_seo.suggestion_provider',
            array('group' => 'sibling')
        );
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'cmf_seo.error.suggestion_provider.parent',
            'cmf_seo.suggestion_provider',
            array('group' => 'parent')
        );

        $this->assertContainerBuilderHasParameter(
            'cmf_seo.error.templates',
            array('html' => 'CmfSeoBundle:Exception:error.html.twig')
        );

        $this->assertContainerBuilderHasService(
            'cmf_seo.error.exclusion_matcher',
            'Symfony\Cmf\Bundle\SeoBundle\Matcher\ExclusionMatcher'
        );
        $attributes = array();
        foreach ($exclusionRules as $key => $rule) {
            $attributes[$key] = array();
            foreach ($rule as $matcher) {
                $attributes[$key][] = $matcher;
            }
            $attributes[$key][] = array();
        }
        $this->assertMatcherCreated($attributes);
    }

    /**
     * @param array $arguments
     */
    private function assertMatcherCreated(array $arguments)
    {
        $count = 0;
        foreach ($this->container->getDefinitions() as $id => $definition) {
            if ($definition instanceof DefinitionDecorator &&
                $definition->getParent() === 'cmf_seo.error.request_matcher'
            ) {
                ++$count;
                $this->assertNotNull($definition);
                $this->assertEquals($arguments[$count - 1], $definition->getArguments());
            }
        }

        $this->assertEquals(2, $count);
    }

    public function testSitemapConfiguration()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'DoctrinePHPCRBundle' => true,
                'CmfRoutingBundle' => true,
            )
        );

        $this->load(array(
            'persistence' => array(
                'phpcr' => true,
            ),
            'alternate_locale' => array(
                'enabled' => true,
            ),
            'sitemap' => array(
                'defaults' => array(
                    'default_change_frequency' => 'global-frequency',
                ),
                'configurations' => array(
                    'default' => array(
                        'default_change_frequency' => 'some-to-test',
                        'templates' => array(
                            'xml' => 'test.xml',
                            'html' => 'test.html',
                            'json' => 'test.json',
                        ),
                    ),
                    'some-other' => array(
                        'default_change_frequency' => 'some-other-to-test',
                        'templates' => array(
                            'xml' => 'test-other.xml',
                            'html' => 'test-other.html',
                            'json' => 'test-other.json',
                        ),
                    ),
                ),
            ),
        ));

        $this->assertContainerBuilderHasParameter('cmf_seo.sitemap.default_change_frequency', 'global-frequency');

        $this->assertContainerBuilderHasParameter(
            'cmf_seo.sitemap.configurations',
            array(
                'default' => array(
                    'templates' => array(
                        'xml' => 'test.xml',
                        'html' => 'test.html',
                        'json' => 'test.json',
                    ),
                ),
                'some_other' => array(
                    'templates' => array(
                        'xml' => 'test-other.xml',
                        'html' => 'test-other.html',
                        'json' => 'test-other.json',
                    ),
                ),
            )
        );

        $this->assertContainerBuilderHasService(
            'cmf_seo.sitemap.controller',
            'Symfony\Cmf\Bundle\SeoBundle\Controller\SitemapController'
        );
        $this->assertContainerBuilderHasService(
            'cmf_seo.sitemap.loader_chain',
            'Symfony\Cmf\Bundle\SeoBundle\Sitemap\LoaderChain'
        );
        $this->assertContainerBuilderHasService(
            'cmf_seo.sitemap.guesser_chain',
            'Symfony\Cmf\Bundle\SeoBundle\Sitemap\GuesserChain'
        );
        $this->assertContainerBuilderHasService(
            'cmf_seo.sitemap.phpcr_loader',
            'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SitemapDocumentProvider'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'cmf_seo.sitemap.phpcr_loader',
            'cmf_seo.sitemap.loader',
            array('priority' => -2, 'sitemap' => 'default,some_other')
        );
        $this->assertContainerBuilderHasService(
            'cmf_seo.sitemap.voter_chain',
            'Symfony\Cmf\Bundle\SeoBundle\Sitemap\VoterChain'
        );
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'cmf_seo.sitemap.publish_workflow_voter',
            'cmf_seo.sitemap.voter',
            array('priority' => -2, 'sitemap' => 'default,some_other')
        );
        $this->assertContainerBuilderHasService(
            'cmf_seo.sitemap.provider',
            'Symfony\Cmf\Bundle\SeoBundle\Sitemap\UrlInformationProvider'
        );

        $guessers = array(
            'cmf_seo.sitemap.guesser.seo_metadata_title',
            'cmf_seo.sitemap.guesser.alternate_locales',
            'cmf_seo.sitemap.guesser.location',
            'cmf_seo.sitemap.guesser.default_change_frequency',
        );
        foreach ($guessers as $guesser) {
            $this->assertContainerBuilderHasServiceDefinitionWithTag(
                $guesser,
                'cmf_seo.sitemap.guesser',
                array('priority' => -2, 'sitemap' => 'default,some_other')
            );
        }
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'cmf_seo.sitemap.guesser.alternate_locales',
            0,
            new Reference('cmf_seo.alternate_locale.provider_phpcr')
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'cmf_seo.sitemap.guesser.default.default_change_frequency',
            0,
            'some-to-test'
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'cmf_seo.sitemap.guesser.some_other.default_change_frequency',
            0,
            'some-other-to-test'
        );
    }

    public function testDefaultTemplatesSet()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'DoctrinePHPCRBundle' => true,
                'CmfRoutingBundle' => true,
            )
        );
        $this->load(array(
            'persistence' => array(
                'phpcr' => true,
            ),
            'alternate_locale' => array(
                'enabled' => true,
            ),
            'sitemap' => array(
                'defaults' => array(
                    'default_change_frequency' => 'global-frequency',
                ),
            ),
            ));

        $this->assertContainerBuilderHasParameter(
            'cmf_seo.sitemap.configurations',
            array(
                'sitemap' => array(
                    'templates' => array(
                        'html' => 'CmfSeoBundle:Sitemap:index.html.twig',
                        'xml' => 'CmfSeoBundle:Sitemap:index.xml.twig',
                    ),
                ),
            )
        );
    }

    public function testDisablingSitemapHelpers()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'DoctrinePHPCRBundle' => true,
                'CmfRoutingBundle' => true,
            )
        );
        $this->load(array(
            'persistence' => array(
                'phpcr' => true,
            ),
            'alternate_locale' => array(
                'enabled' => true,
            ),
            'sitemap' => array(
                'defaults' => array(
                    'default_change_frequency' => 'global-frequency',
                    'loaders' => '_all',
                    'guessers' => 'cmf_seo.sitemap.guesser.default_change_frequency',
                    'voters' => '_none',
                ),
            ),
        ));

        $this->assertContainerBuilderHasService('cmf_seo.sitemap.phpcr_loader');
        $this->assertContainerBuilderHasService('cmf_seo.sitemap.guesser.default_change_frequency');
        $this->assertContainerBuilderNotHasService('cmf_seo.sitemap.guesser.location');
        $this->assertContainerBuilderNotHasService('cmf_seo.sitemap.guesser.location');
        $this->assertContainerBuilderNotHasService('cmf_seo.sitemap.guesser.alternate_locales');
        $this->assertContainerBuilderNotHasService('cmf_seo.sitemap.guesser.seo_metadata_title');
        $this->assertContainerBuilderNotHasService('cmf_seo.sitemap.publish_workflow_voter');
    }

    public function testDisableSeoContentListener()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'DoctrinePHPCRBundle' => true,
                'CmfRoutingBundle' => true,
            )
        );
        $this->load(array(
            'persistence' => array(
                'phpcr' => true,
            ),
            'content_listener' => array(
                'enabled' => false,
            ),
        ));

        $this->assertContainerBuilderNotHasService(
            'cmf_seo.event_listener.seo_content'
        );
    }
}
