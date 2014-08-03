<?php


namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class RegisterBestMatcherPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws LogicException If a tagged service is not public.
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('"cmf_seo.error_handling.matcher.presentation')) {
            return;
        }

        $strategyDefinition = $container->getDefinition('"cmf_seo.error_handling.matcher.presentation');
        $taggedServices = $container->findTaggedServiceIds('cmf_seo.best_matcher');

        foreach ($taggedServices as $id => $attributes) {
            $definition = $container->getDefinition($id);
            if (!$definition->isPublic()) {
                throw new LogicException(sprintf('Strategy "%s" must be public.', $id));
            }

            if (!isset($attributes['type'])) {
                throw new LogicException('You have to set a BestMatcher type.');
            }

            $strategyDefinition->addMethodCall('addMatcher', array(
                new Reference($id),
                $attributes['type']
            ));
        }
    }
}
