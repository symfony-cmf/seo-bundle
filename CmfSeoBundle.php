<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle;

use Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\RegisterExtractorsPass;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\UnescapePlaceholdersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CmfSeoBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterExtractorsPass());
        $container->addCompilerPass(new UnescapePlaceholdersPass(array(
            'cmf_seo.title',
            'cmf_seo.description',
        )), PassConfig::TYPE_OPTIMIZE);

        if (class_exists('Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass')) {
            $container->addCompilerPass(
                DoctrinePhpcrMappingsPass::createXmlMappingDriver(
                    array(
                        realpath(__DIR__ . '/Resources/config/doctrine-model') => 'Symfony\Cmf\Bundle\SeoBundle\Model',
                        realpath(__DIR__ . '/Resources/config/doctrine-phpcr') => 'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr',
                    ),
                    array('cmf_seo.manager_name'),
                    'cmf_seo.backend_type_phpcr'
                )
            );
        }
    }
}
