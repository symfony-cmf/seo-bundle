<?php

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
    /**
     * Register the compiler pass under test, just like you would do inside a bundle's load()
     * method:
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
        $nonProviderService = new Definition();
        $this->setDefinition('some_service', $nonProviderService);

        $providerService = new Definition();
        $providerService->addTag('cmf_seo.sitemap.'.$tagName);
        $this->setDefinition($tagName.'_service', $providerService);

        $providerServiceWithPriority = new Definition();
        $providerServiceWithPriority->addTag(
            'cmf_seo.sitemap.'.$tagName,
            array('priority' => 1)
        );
        $this->setDefinition($tagName.'_service_priority', $providerServiceWithPriority);

        $providerServiceWithSitemap = new Definition();
        $providerServiceWithSitemap->addTag(
            'cmf_seo.sitemap.'.$tagName,
            array('sitemap' => 'some-sitemap')
        );
        $this->setDefinition($tagName.'_service_sitemap', $providerServiceWithSitemap);

        $providerServiceWithMultipleSitemap = new Definition();
        $providerServiceWithMultipleSitemap->addTag(
            'cmf_seo.sitemap.'.$tagName,
            array('sitemap' => 'some-sitemap,some-other')
        );
        $this->setDefinition($tagName.'_service_sitemap_multiple', $providerServiceWithMultipleSitemap);

        $chainProvider = new Definition();
        $this->setDefinition('cmf_seo.sitemap.'.$serviceName, $chainProvider);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.'.$serviceName,
            'addItem',
            array(new Reference($tagName.'_service'), null, null)
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.'.$serviceName,
            'addItem',
            array(new Reference($tagName.'_service_priority'), 1, null)
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.'.$serviceName,
            'addItem',
            array(new Reference($tagName.'_service_sitemap'), null, 'some-sitemap')
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.'.$serviceName,
            'addItem',
            array(new Reference($tagName.'_service_sitemap_multiple'), null, 'some-sitemap')
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.sitemap.'.$serviceName,
            'addItem',
            array(new Reference($tagName.'_service_sitemap_multiple'), null, 'some-other')
        );
    }

    public function tagProvider()
    {
        return array(
            array('loader', 'loader_chain'),
            array('voter', 'voter_chain'),
            array('guesser', 'guesser_chain'),
        );
    }
}
