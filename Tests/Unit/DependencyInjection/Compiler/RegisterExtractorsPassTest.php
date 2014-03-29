<?php

namespace Symfony\Cmf\SeoBundle\Tests\Unit\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\RegisterExtractorsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterExtractorsPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterExtractorsPass());
    }

    public function testRegistersServicesWithExtractorTag()
    {
        $nonExtractorService = new Definition();
        $this->setDefinition('some_service', $nonExtractorService);

        $extractorService = new Definition();
        $extractorService->addTag('cmf_seo.extractor');
        $this->setDefinition('extractor_service', $extractorService);

        $presentationService = new Definition();
        $this->setDefinition('cmf_seo.presentation', $presentationService);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.presentation',
            'addExtractor',
            array(
                new Reference('extractor_service'),
            )
        );
    }
}
