<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\RegisterExtractorsPass;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\RegisterSuggestionProviderPass;
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\Compiler\RegisterUrlInformationProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CmfSeoBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterExtractorsPass());
        $container->addCompilerPass(new RegisterSuggestionProviderPass());
        $container->addCompilerPass(new RegisterUrlInformationProviderPass());

        $this->buildPhpcrCompilerPass($container);
        $this->buildOrmCompilerPass($container);
    }

    /**
     * Creates and registers compiler passes for PHPCR-ODM mapping if both the
     * phpcr-odm and the phpcr-bundle are present.
     *
     * @param ContainerBuilder $container
     */
    private function buildPhpcrCompilerPass(ContainerBuilder $container)
    {
        if (!class_exists('Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass')
            || !class_exists('Doctrine\ODM\PHPCR\Version')
        ) {
            return;
        }

        $container->addCompilerPass(
            DoctrinePhpcrMappingsPass::createXmlMappingDriver(
                [
                    $this->getPath().'/Resources/config/doctrine-model' => 'Symfony\Cmf\Bundle\SeoBundle\Model',
                    $this->getPath().'/Resources/config/doctrine-phpcr' => 'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr',
                ],
                ['cmf_seo.dynamic.persistence.phpcr.manager_name'],
                'cmf_seo.backend_type_phpcr',
                ['CmfSeoBundle' => 'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr']
            )
        );
    }

    /**
     * Creates and registers compiler passes for ORM mappings if both doctrine
     * ORM and a suitable compiler pass implementation are available.
     *
     * @param ContainerBuilder $container
     */
    private function buildOrmCompilerPass(ContainerBuilder $container)
    {
        if (!class_exists('Doctrine\ORM\Version')
            || !class_exists('Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterMappingsPass')
            || !class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')
        ) {
            return;
        }

        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createXmlMappingDriver(
                [
                    $this->getPath().'/Resources/config/doctrine-model' => 'Symfony\Cmf\Bundle\SeoBundle\Model',
                ],
                ['cmf_seo.dynamic.persistence.orm.manager_name'],
                'cmf_seo.backend_type_orm',
                ['CmfSeoBundle' => 'Symfony\Cmf\Bundle\SeoBundle\Model']
            )
        );
    }
}
