<?php

use Symfony\Cmf\Component\Testing\HttpKernel\TestKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends TestKernel
{
    public function configure()
    {
        $this->requireBundleSets(array('default', 'sonata_admin'));

        if ('phpcr' === $this->environment) {
            $this->requireBundleSet('phpcr_odm');
        } elseif ('orm' === $this->environment) {
            $this->requireBundleSet('doctrine_orm');
        }

        $this->addBundles(array(
            new \Sonata\SeoBundle\SonataSeoBundle(),
            new \Symfony\Cmf\Bundle\SeoBundle\CmfSeoBundle(),
            new \Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
            new \Burgov\Bundle\KeyValueFormBundle\BurgovKeyValueFormBundle(),
        ));
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.php');
        $loader->load(__DIR__.'/config/config_'.$this->environment.'.php');
    }
}
