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
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class RegisterSuggestionProviderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws LogicException if a tagged service is not public
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('cmf_seo.error.suggestion_provider.controller')) {
            return;
        }

        $presentationDefinition = $container->getDefinition('cmf_seo.error.suggestion_provider.controller');
        $taggedServices = $container->findTaggedServiceIds('cmf_seo.suggestion_provider');
        $provider = [];
        foreach ($taggedServices as $id => $attributes) {
            $definition = $container->getDefinition($id);
            if (!$definition->isPublic()) {
                throw new LogicException(sprintf('Matcher "%s" must be public.', $id));
            }

            $group = null;
            foreach ($attributes as $attribute) {
                if (isset($attribute['group'])) {
                    $group = $attribute['group'];

                    break;
                }
            }
            $group = $group ?: 'default';
            $provider[] = ['provider' => new Reference($id), 'group' => $group];
        }

        $presentationDefinition->replaceArgument(4, $provider);
    }
}
