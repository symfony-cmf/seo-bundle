<?php

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CmfSeoExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('admin.xml');

        $this->loadTitle($config['title'], $loader, $container);

        $this->loadContent($config['content'], $loader, $container);

        if ($config['persistence']['phpcr']['enabled']) {
            $this->loadPhpcr($config['persistence']['phpcr'], $loader, $container);
        }
    }

    public function loadPhpcr($config, XmlFileLoader $loader, ContainerBuilder $container)
    {
        $container->setParameter($this->getAlias() . '.backend_type_phpcr', true);

        $keys = array(
            'admin_class'               => 'admin_extension.class',
            'document_class'             => 'document.class',
            'content_basepath'          => 'content_basepath',
        );

        foreach ($keys as $sourceKey => $targetKey) {
            if (isset($config[$sourceKey])) {
                $container->setParameter($this->getAlias() . '.persistence.phpcr.'.$targetKey, $config[$sourceKey]);
            }
        }

        if ($config['use_sonata_admin']) {
            $this->loadSonataAdmin($config, $loader, $container);
        }
    }

    public function loadSonataAdmin($config, XmlFileLoader $loader, ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if ('auto' === $config['use_sonata_admin'] && !isset($bundles['SonataDoctrinePHPCRAdminBundle'])) {
            return;
        }

        $loader->load('admin.xml');
    }

    private function loadTitle($title, $loader, ContainerBuilder $container)
    {
        $container->setParameter($this->getAlias().'.title', true);

        foreach ($title as $key => $value) {
            $container->setParameter($this->getAlias().'.title.'.$key, $value);
        }
    }

    private function loadContent($content, $loader, ContainerBuilder $container)
    {
        $container->setParameter($this->getAlias().'.content', true);

        foreach ($content as $key => $value) {
            $container->setParameter($this->getAlias().'.content.'.$key, $value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace()
    {
        return 'http://cmf.symfony.com/schema/dic/seo';
    }
}
