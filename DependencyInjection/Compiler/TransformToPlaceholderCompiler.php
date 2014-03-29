<?php

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
     * @var array
     */
    private $parametersToFix;

    public function __construct(array $parametersToFix)
    {
        $this->parametersToFix = $parametersToFix;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($this->parametersToFix as $parameter) {
            if ($container->hasParameter($parameter)) {
                $container->setParameter($parameter, str_replace('%%', '%', $container->getParameter($parameter)));
            }
        }
    }
}
