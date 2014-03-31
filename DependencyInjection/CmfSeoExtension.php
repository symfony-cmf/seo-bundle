<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;

/**
 * Loads and manages the bundle configuration.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
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

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('extractors.xml');

        $this->loadSeoParameters($config, $container);

        if (empty($config['content_key'])) {
            if (! class_exists('Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter')) {
                throw new \RuntimeException('You need to set the content_key when not using the CmfRoutingBundle DynamicRouter');
            }
            $contentKey = DynamicRouter::CONTENT_KEY;
        } else {
            $contentKey = $config['content_key'];
        }
        $container->setParameter($this->getAlias() . '.content_key', $contentKey);

        if ($config['persistence']['phpcr']['enabled']) {
            $this->loadPhpcr($config['persistence']['phpcr'], $loader, $container);
        }
    }

    /**
     * Loads the phpcr integration.
     *
     * @param array            $config
     * @param XmlFileLoader    $loader
     * @param ContainerBuilder $container
     */
    public function loadPhpcr(array $config, XmlFileLoader $loader, ContainerBuilder $container)
    {
        $container->setParameter($this->getAlias() . '.backend_type_phpcr', true);

        $keys = array(
            'admin_class'      => 'admin_extension.class',
            'document_class'   => 'document.class',
        );

        foreach ($keys as $sourceKey => $targetKey) {
            if (isset($config[$sourceKey])) {
                $container->setParameter($this->getAlias() . '.persistence.phpcr.'.$targetKey, $config[$sourceKey]);
            }
        }

        if ($config['use_metadata_listener']) {
            $loader->load('doctrine-phpcr.xml');
        }

        if ($config['use_sonata_admin']) {
            $this->loadSonataAdmin($config, $loader, $container);
        }
    }

    /**
     * Loads the sonata admin extension.
     *
     * @param array            $config
     * @param XmlFileLoader    $loader
     * @param ContainerBuilder $container
     */
    public function loadSonataAdmin(array $config, XmlFileLoader $loader, ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if ('auto' === $config['use_sonata_admin'] && !isset($bundles['SonataDoctrinePHPCRAdminBundle'])) {
            return;
        }

        $loader->load('admin.xml');
    }

    /**
     * Puts the seo parameters into the container
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function loadSeoParameters(array $config, ContainerBuilder $container)
    {
        $params = array('translation_domain', 'title', 'description', 'original_route_pattern');

        foreach ($params as $param) {
            $value = isset($config[$param]) ? $config[$param] : null;
            $container->setParameter($this->getAlias().'.'.$param, $value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace()
    {
        return 'http://cmf.symfony.com/schema/dic/seo';
    }

    /**
        * {@inheritDoc}
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }
}
