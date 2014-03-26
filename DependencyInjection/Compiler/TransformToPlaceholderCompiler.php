<?php

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Transform the converted placeholders for the Translation component back 
 * again, to avoid problems with the ResolverParameterCompiler.
 *
 * This compiler must be registered as PassConfig::TYPE_OPTIMIZE.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
class TransformToPlaceholderCompiler implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('cmf_seo.title')) {
            $container->setParameter('cmf_seo.title', strtr($container->getParameter('cmf_seo.title'), '{}', '%%'));
        }
    }
}
