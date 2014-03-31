<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\CmfSeoExtension;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Configuration;
use Symfony\Cmf\Bundle\SeoBundle\Model\SeoPresentation;

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
            'translation_domain'     => null,
            'title'                  => 'default_title',
            'description'            => 'default_description',
            'original_route_pattern' => SeoPresentation::ORIGINAL_URL_CANONICAL,
            'persistence' => array(
                'phpcr' => array('enabled' => false),
                'orm' => array('enabled' => false),
            ),
            'metadata_listener'      => 'auto',
            'sonata_admin_extension' => 'auto',
        );

        $sources = array_map(function ($path) {
                return __DIR__.'/../../Resources/Fixtures/'.$path;
        }, array(
                'config/config.yml',
                'config/config.php',
                'config/config.xml',
        ));

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }
}
