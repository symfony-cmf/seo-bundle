<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\CmfSeoExtension;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Configuration;
use Symfony\Cmf\Bundle\SeoBundle\SeoPresentation;

/**
 * This test will try to cover all configs.
 *
 * Means check if all available formats are equal.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    protected function getContainerExtension()
    {
        return new CmfSeoExtension();
    }

    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testDefaultsForAllConfigFormats()
    {
        $expectedConfiguration = [
            'title' => 'default_title',
            'description' => 'default_description',
            'persistence' => [
                'phpcr' => [
                    'enabled' => false,
                    'manager_name' => null,
                    'content_basepath' => '/cms/content',
                ],
                'orm' => [
                    'enabled' => false,
                    'manager_name' => null,
                ],
            ],
            'translation_domain' => 'messages',
            'original_route_pattern' => SeoPresentation::ORIGINAL_URL_CANONICAL,
            'alternate_locale' => [
                'enabled' => false,
                'provider_id' => null,
            ],
            'sitemap' => [
                'enabled' => false,
                'configurations' => [],
                'defaults' => [
                    'default_change_frequency' => 'always',
                    'templates' => [
                        'html' => 'CmfSeoBundle:Sitemap:index.html.twig',
                        'xml' => 'CmfSeoBundle:Sitemap:index.xml.twig',
                    ],
                    'loaders' => ['_all'],
                    'guessers' => ['_all'],
                    'voters' => ['_all'],
                ],
            ],
            'content_listener' => [
                'enabled' => true,
                'content_key' => 'contentDocument',
            ],
            'form' => [
                'data_class' => [
                    'seo_metadata' => null,
                ],
                'options' => [
                    'generic_metadata' => 'auto',
                ],
            ],
        ];

        $sources = array_map(function ($path) {
            return __DIR__.'/../../Resources/Fixtures/'.$path;
        }, [
            'config/config.yml',
            'config/config.php',
            'config/config.xml',
        ]);

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }

    public function testSitemapXmlConfigurations()
    {
        $expectedConfiguration = [
            'sitemap' => [
                'enabled' => true,
                'configurations' => [
                    'sitemap' => [
                        'default_change_frequency' => 'never',
                        'templates' => [
                            'xml' => 'test.xml',
                            'html' => 'test.html',
                        ],
                        'loaders' => [],
                        'guessers' => [],
                        'voters' => [],
                    ],
                ],
                'defaults' => [
                    'default_change_frequency' => 'always',
                    'templates' => [
                        'html' => 'foo.html.twig',
                        'xml' => 'foo.xml.twig',
                    ],
                    'loaders' => ['_all'],
                    'guessers' => ['_all'],
                    'voters' => ['_all'],
                ],
            ],
            'persistence' => [
                'phpcr' => [
                    'enabled' => false,
                    'manager_name' => null,
                    'content_basepath' => '/cms/content',
                ],
                'orm' => [
                    'enabled' => false,
                    'manager_name' => null,
                ],
            ],
            'translation_domain' => 'messages',
            'original_route_pattern' => SeoPresentation::ORIGINAL_URL_CANONICAL,
            'alternate_locale' => [
                'enabled' => false,
                'provider_id' => null,
            ],
            'content_listener' => [
                'enabled' => true,
                'content_key' => 'contentDocument',
            ],
            'form' => [
                'data_class' => [
                    'seo_metadata' => null,
                ],
                'options' => [
                    'generic_metadata' => 'auto',
                ],
            ],
        ];

        $sources = array_map(function ($path) {
            return __DIR__.'/../../Resources/Fixtures/'.$path;
        }, [
            'config/config_sitemap.xml',
        ]);

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }

    public function testErrorHandlingXmlConfigurations()
    {
        $expectedConfiguration = [
            'error' => [
                'enable_parent_provider' => true,
                'enable_sibling_provider' => true,
                'exclusion_rules' => [
                    [
                        'path' => 'some/path',
                        'ips' => 'IP',
                        'methods' => 'GET',
                        'host' => 'test.de',
                    ],
                    [
                        'path' => 'some-other/path',
                        'ips' => 'IPs',
                        'methods' => 'POST',
                        'host' => 'test-dev.de',
                    ],
                ],
                'templates' => [
                    'html' => 'CmfSeoBundle:Exception:error.html.twig',
                ],
            ],
            'translation_domain' => 'messages',
            'original_route_pattern' => SeoPresentation::ORIGINAL_URL_CANONICAL,
            'persistence' => [
                'phpcr' => [
                    'enabled' => false,
                    'manager_name' => null,
                    'content_basepath' => '/cms/content',
                ],
                'orm' => [
                    'enabled' => false,
                    'manager_name' => null,
                ],
            ],
            'alternate_locale' => [
                'enabled' => false,
                'provider_id' => null,
            ],
            'sitemap' => [
                'enabled' => false,
                'configurations' => [],
                'defaults' => [
                    'default_change_frequency' => 'always',
                    'templates' => [
                        'html' => 'CmfSeoBundle:Sitemap:index.html.twig',
                        'xml' => 'CmfSeoBundle:Sitemap:index.xml.twig',
                    ],
                    'loaders' => ['_all'],
                    'guessers' => ['_all'],
                    'voters' => ['_all'],
                ],
            ],
            'content_listener' => [
                'enabled' => true,
                'content_key' => 'contentDocument',
            ],
            'form' => [
                'data_class' => [
                    'seo_metadata' => null,
                ],
                'options' => [
                    'generic_metadata' => 'auto',
                ],
            ],
        ];

        $sources = array_map(function ($path) {
            return __DIR__.'/../../Resources/Fixtures/'.$path;
        }, [
            'config/config_error.xml',
        ]);

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }
}
