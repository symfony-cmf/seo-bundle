<?php

namespace Symfony\Cmf\Bundle\SeoBundle;

use Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\ExtractorTagCompiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Definition;

class CmfSeoBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        if (class_exists('Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass')) {
            $container->addCompilerPass(
                DoctrinePhpcrMappingsPass::createXmlMappingDriver(
                    array(
                        realpath(__DIR__ . '/Resources/config/doctrine-phpcr') => 'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr',
                    )
                )
            );
        }

        $container->addCompilerPass(new ExtractorTagCompiler());
    }
}
