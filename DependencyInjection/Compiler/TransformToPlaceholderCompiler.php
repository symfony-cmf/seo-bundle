<?php

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Transforms the escaped percent characters back to normal percent 
 * characters, so it can be handled by tools like the Translator.
 *
 * This compiler must be registered as PassConfig::TYPE_OPTIMIZE, otherwise it 
 * causes conflicts with the ResolveParameterPass.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
class TransformToPlaceholderCompiler implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $parameterNames;

    /**
     * @param array $parameters The names of the parameters which should be unescaped
     */
    public function __construct(array $parameterNames)
    {
        $this->parameterNames = $parameterNames;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($this->parameterNames as $parameterName) {
            if ($container->hasParameter($parameterName)) {
                $container->setParameter($parameterName, str_replace('%%', '%', $container->getParameter($parameterName)));
            }
        }
    }
}
