<?php


namespace Symfony\Cmf\SeoBundle\Tests\Unit\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\RegisterBestMatcherPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class RegisterBestMatcherPassTest extends AbstractCompilerPassTestCase
{

    /**
     * Register the compiler pass under test, just like you would do inside a bundle's load()
     * method:
     *
     *   $container->addCompilerPass(new MyCompilerPass());
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterBestMatcherPass());
    }

    public function testRegistersServicesWithMatcherTag()
    {
        $nonMatcherService = new Definition();
        $this->setDefinition('some_service', $nonMatcherService);

        $matcherServiceWithGroup = new Definition();
        $matcherServiceWithGroup->addTag('cmf_seo.best_matcher', array('group' => 'some-group'));
        $this->setDefinition('matcher.with_group', $matcherServiceWithGroup);

        $matcherPresentationService = new Definition();
        $this->setDefinition('cmf_seo.error_handling.matcher.presentation', $matcherPresentationService);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.error_handling.matcher.presentation',
            'addMatcher',
            array(new Reference('matcher.with_group'), 'some-group')
        );
    }

    public function testRegistersServicesWithMatcherTagWithoutGroup()
    {
        $nonMatcherService = new Definition();
        $this->setDefinition('some_service', $nonMatcherService);

        $matcherServiceWithOutGroup = new Definition();
        $matcherServiceWithOutGroup->addTag('cmf_seo.best_matcher');
        $this->setDefinition('matcher.without_group', $matcherServiceWithOutGroup);

        $matcherPresentationService = new Definition();
        $this->setDefinition('cmf_seo.error_handling.matcher.presentation', $matcherPresentationService);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cmf_seo.error_handling.matcher.presentation',
            'addMatcher',
            array(new Reference('matcher.without_group'), 'default')
        );
    }
}
