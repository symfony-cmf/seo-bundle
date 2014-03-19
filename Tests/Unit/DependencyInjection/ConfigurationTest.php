<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\CmfSeoExtension;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Configuration;

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
        $expectedConfiguration = array(
            'title'   => array(
                'pattern'   => 'prepend',
                'default'   => 'Default title',
                'separator' => '',
            ),
            'content' => array('pattern' => 'canonical'),
            'persistence' => array(
                'phpcr' => array(
                    'document_class'    => 'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent',
                    'admin_class'       => 'Symfony\Cmf\Bundle\SeoBundle\Admin\SeoContentAdminExtension',
                    'content_basepath'  => '/cms/content',
                    'use_sonata_admin'  => 'auto',
                    'enabled'           => false,
                ),
            ),
        );

        $sources = array_map(function ($path) {
                return __DIR__.'/../../Resources/Fixtures/'.$path;
        }, array(
                'config/default_config.yml',
                'config/default_config.php',
                'config/default_config.xml',
        ));

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }

    public function testMultilangForAllConfigFormats()
    {
        $expectedConfiguration = array(
            'title'   => array(
                'pattern'   => 'prepend',
                'default'   => array(
                    'de'    => 'Default Titel',
                    'en'    => 'Default title',
                ),
                'separator' => '',
            ),
            'content' => array('pattern' => 'canonical'),
            'persistence' => array(
                'phpcr' => array(
                    'document_class'    => 'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent',
                    'admin_class'       => 'Symfony\Cmf\Bundle\SeoBundle\Admin\SeoContentAdminExtension',
                    'content_basepath'  => '/cms/content',
                    'use_sonata_admin'  => 'auto',
                    'enabled'           => false,
                ),
            ),
        );

        $sources = array_map(function ($path) {
                return __DIR__.'/../../Resources/Fixtures/'.$path;
        }, array(
            'config/multilang_config.yml',
            'config/multilang_config.php',
            'config/multilang_config.xml',
        ));

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }

    public function testFullForAllConfigFormats()
    {
        $expectedConfiguration = array(
            'title'   => array(
                'pattern'   => 'append',
                'default'   => 'Default title',
                'separator' => ' | ',
            ),
            'content' => array('pattern' => 'redirect'),
            'persistence' => array(
                'phpcr' => array(
                    'document_class'    => 'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoAwareContent',
                    'admin_class'       => 'Symfony\Cmf\Bundle\SeoBundle\Admin\SeoContentAdminExtension',
                    'content_basepath'  => '/cms/content',
                    'use_sonata_admin'  => true,
                    'enabled'           => true,
                ),
            ),
        );

        $sources = array_map(function ($path) {
                return __DIR__.'/../../Resources/Fixtures/'.$path;
        }, array(
                'config/full_config.yml',
                'config/full_config.php',
                'config/full_config.xml',
        ));

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }
}
