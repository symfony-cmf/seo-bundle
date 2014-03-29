<?php

namespace Symfony\Cmf\Bundle\SeoBundle;

use Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\ExtractorTagCompiler;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\TransformToPlaceholderCompiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

class CmfSeoBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        if (class_exists('Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass')) {
            $container->addCompilerPass(
                DoctrinePhpcrMappingsPass::createXmlMappingDriver(
                    array(
                        realpath(__DIR__ . '/Resources/config/doctrine-model') => 'Symfony\Cmf\Bundle\SeoBundle\Model',
                        realpath(__DIR__ . '/Resources/config/doctrine-phpcr') => 'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr',
                    )
                )
            );
        }

        $container->addCompilerPass(new ExtractorTagCompiler());
        $container->addCompilerPass(new TransformToPlaceholderCompiler(array(
            'cmf_seo.title',
            'cmf_seo.description',
        )), PassConfig::TYPE_OPTIMIZE);
    }
}
