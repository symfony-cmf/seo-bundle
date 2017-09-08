<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
class RegisterExtractorsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws LogicException if a tagged service is not public
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('cmf_seo.presentation')) {
            return;
        }

        $strategyDefinition = $container->getDefinition('cmf_seo.presentation');
        $taggedServices = $container->findTaggedServiceIds('cmf_seo.extractor');

        foreach ($taggedServices as $id => $attributes) {
            $definition = $container->getDefinition($id);
            if (!$definition->isPublic()) {
                throw new LogicException(sprintf('Strategy "%s" must be public.', $id));
            }

            $priority = 0;
            foreach ($attributes as $attribute) {
                if (isset($attribute['priority'])) {
                    $priority = $attribute['priority'];

                    break;
                }
            }

            $strategyDefinition->addMethodCall('addExtractor', [
                new Reference($id),
                $priority,
            ]);
        }
    }
}
