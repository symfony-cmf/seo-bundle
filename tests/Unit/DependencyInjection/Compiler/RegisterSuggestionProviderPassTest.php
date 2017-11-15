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
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\RegisterSuggestionProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class RegisterSuggestionProviderPassTest extends AbstractCompilerPassTestCase
{
    /**
     * Register the compiler pass under test, just like you would do inside a bundle's load()
     * method:.
     *
     *   $container->addCompilerPass(new MyCompilerPass());
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterSuggestionProviderPass());
    }

    public function testRegistersServicesWithMatcherTag()
    {
        $nonMatcherService = new Definition();
        $this->setDefinition('some_service', $nonMatcherService);

        $matcherServiceWithGroup = new Definition();
        $matcherServiceWithGroup->addTag('cmf_seo.suggestion_provider', ['group' => 'some-group']);
        $this->setDefinition('matcher.with_group', $matcherServiceWithGroup);

        $matcherPresentationService = new Definition();
        $matcherPresentationService->setArguments([1, 1, 2, 3, []]);
        $this->setDefinition('cmf_seo.error.suggestion_provider.controller', $matcherPresentationService);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'cmf_seo.error.suggestion_provider.controller',
            4,
            [['provider' => new Reference('matcher.with_group'), 'group' => 'some-group']]
        );
    }

    public function testRegistersServicesWithMatcherTagWithoutGroup()
    {
        $nonMatcherService = new Definition();
        $this->setDefinition('some_service', $nonMatcherService);

        $matcherServiceWithOutGroup = new Definition();
        $matcherServiceWithOutGroup->addTag('cmf_seo.suggestion_provider');
        $this->setDefinition('matcher.without_group', $matcherServiceWithOutGroup);

        $matcherPresentationService = new Definition();
        $matcherPresentationService->setArguments([1, 1, 2, 3, []]);
        $this->setDefinition('cmf_seo.error.suggestion_provider.controller', $matcherPresentationService);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'cmf_seo.error.suggestion_provider.controller',
            4,
            [['provider' => new Reference('matcher.without_group'), 'group' => 'default']]
        );
    }
}
