<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\SeoBundle\Tests\Unit\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\RegisterUrlInformationProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class RegisterUrlInformationProviderPassTest extends AbstractCompilerPassTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->container->setParameter('cmf_seo.sitemap.configurations', []);
        $nonProviderService = new Definition();
        $this->setDefinition('some_service', $nonProviderService);

        foreach ($this->tagProvider() as $service) {
            $chain = new Definition();
            $this->setDefinition('cmf_seo.sitemap.'.$service[1], $chain);
        }
    }

    /**
     * Register the compiler pass under test, just like you would do inside a bundle's load()
     * method:.
     *
     *   $container->addCompilerPass(new MyCompilerPass());
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterUrlInformationProviderPass());
    }

    /**
     * @dataProvider tagProvider
     */
    public function testTags($tagName, $serviceName)
    {
        $taggedService = new Definition();
        $taggedService->addTag('cmf_seo.sitemap.'.$tagName);
        $this->setDefinition($tagName.'_service', $taggedService);

        $providerServiceWithPriority = new Definition();
        $providerServiceWithPriority->addTag(
            'cmf_seo.sitemap.'.$tagName,
            ['priority' => 1]
        );
        $this->setDefinition($tagName.'_service_priority', $providerServiceWithPriority);

        $providerServiceWithSitemap = new Definition();
        $providerServiceWithSitemap->addTag(
            'cmf_seo.sitemap.'.$tagName,
            ['sitemap' => 'some-sitemap']
        );
        $this->setDefinition($tagName.'_service_sitemap', $providerServiceWithSitemap);

        $providerServiceWithMultipleSitemap = new Definition();
        $providerServiceWithMultipleSitemap->addTag(
            'cmf_seo.sitemap.'.$tagName,
            ['sitemap' => 'some-sitemap,some-other']
        );
        $this->setDefinition($tagName.'_service_sitemap_multiple', $providerServiceWithMultipleSitemap);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.'.$serviceName,
            'addItem',
            [new Reference($tagName.'_service'), null, null]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.'.$serviceName,
            'addItem',
            [new Reference($tagName.'_service_priority'), 1, null]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.'.$serviceName,
            'addItem',
            [new Reference($tagName.'_service_sitemap'), null, 'some-sitemap']
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.'.$serviceName,
            'addItem',
            [new Reference($tagName.'_service_sitemap_multiple'), null, 'some-sitemap']
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.'.$serviceName,
            'addItem',
            [new Reference($tagName.'_service_sitemap_multiple'), null, 'some-other']
        );
    }

    public function tagProvider()
    {
        return [
            ['loader', 'loader_chain'],
            ['voter', 'voter_chain'],
            ['guesser', 'guesser_chain'],
        ];
    }
}
