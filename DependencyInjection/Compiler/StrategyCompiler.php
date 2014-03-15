<?php

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This compiler pass will fetch all services which are tagged as
 * seo strategies (cmf_seo.extractor).
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class StrategyCompiler implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     * @throws \Symfony\Component\DependencyInjection\Exception\LogicException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->getDefinition('cmf_seo.presentation')) {
            return;
        }

        $strategyDefinition = $container->getDefinition('cmf_seo.presentation');
        $taggedServices = $container->findTaggedServiceIds('cmf_seo.extractor');

        foreach ($taggedServices as $id => $attributes) {
            $definition = $container->getDefinition($id);
            if (!$definition->isPublic()) {
                throw new LogicException(sprintf('strategy "%s" must be public', $id));
            }

            $strategyDefinition->addMethodCall('addStrategy', array(
                new Reference($id)
            ));
        }
    }
}
