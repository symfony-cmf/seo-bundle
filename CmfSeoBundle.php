<?php

namespace Symfony\Cmf\Bundle\SeoBundle;

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
    }
}
