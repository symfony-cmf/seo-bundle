<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection;

use Symfony\Cmf\Bundle\SeoBundle\CmfSeoBundle;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Loads and manages the bundle configuration.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class CmfSeoExtension extends Extension
{
    /**
     * @var string
     */
    private $defaultAlternateLocaleProviderId;

    /**
     * @var bool Whether the content listener is loaded
     */
    private $contentListenerEnabled = false;

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

        $sonataBundles = array();
        if ($this->isConfigEnabled($container, $config['persistence']['phpcr'])) {
            $container->setParameter($this->getAlias() . '.backend_type_phpcr', true);
            $container->setParameter(
                $this->getAlias() . '.persistence.phpcr.manager_name',
                $config['persistence']['phpcr']['manager_name']
            );
            $sonataBundles[] = 'SonataDoctrinePHPCRAdminBundle';

            $this->loadPhpcr($config['persistence']['phpcr'], $loader, $container);
        }

        if ($this->isConfigEnabled($container, $config['persistence']['orm'])) {
            $container->setParameter($this->getAlias() . '.backend_type_orm', true);
            $container->setParameter(
                $this->getAlias() . '.persistence.orm.manager_name',
                $config['persistence']['orm']['manager_name']
            );
            $sonataBundles[] = 'SonataDoctrineORMBundle';
        }

        if (count($sonataBundles) && $config['sonata_admin_extension']['enabled']) {
            $this->loadSonataAdmin($config['sonata_admin_extension'], $loader, $container, $sonataBundles);
        }

        $errorConfig = isset($config['error']) ? $config['error'] : array();
        $this->loadErrorHandling($errorConfig, $container);

        if ($this->isConfigEnabled($container, $config['sitemap'])) {
            $this->loadSitemapHandling($config['sitemap'], $loader, $container, $this->isConfigEnabled($container, $config['alternate_locale']));
        }

        if ($this->isConfigEnabled($container, $config['content_listener'])) {
            $this->loadContentListener($config['content_listener'], $loader, $container);
        }

        if ($this->isConfigEnabled($container, $config['alternate_locale'])) {
            $this->loadAlternateLocaleProvider($config['alternate_locale'], $container);
        }
    }

    /**
     * Loads the sonata admin extension if at least one supported backend is loaded.
     *
     * @param string|bool      $config    Either 'auto' or true.
     * @param XmlFileLoader    $loader
     * @param ContainerBuilder $container
     * @param array            $sonata    List of sonata bundles that are enabled.
     */
    public function loadSonataAdmin($config, XmlFileLoader $loader, ContainerBuilder $container, array $sonata)
    {
        if ('auto' === $config['enabled']) {
            $bundles = $container->getParameter('kernel.bundles');
            $found = false;
            foreach ($sonata as $bundle) {
                if (isset($bundles[$bundle])) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return;
            }

            if (!isset($bundles['BurgovKeyValueFormBundle'])) {
                throw new InvalidConfigurationException(
                    'To use advanced menu options, you need the burgov/key-value-form-bundle in your project.'
                );
            }
        }

        $container->setParameter($this->getAlias() . '.sonata_admin_extension.form_group', $config['form_group']);
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

    /**
     * @param $config
     * @param XmlFileLoader $loader
     * @param ContainerBuilder $container
     */
    private function loadPhpcr($config, XmlFileLoader $loader, ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (isset($bundles['CmfRoutingBundle'])) {
            $loader->load('phpcr-alternate-locale.xml');
            if (!$this->defaultAlternateLocaleProviderId) {
                $this->defaultAlternateLocaleProviderId = 'cmf_seo.alternate_locale.provider_phpcr';
            }
        }

        $loader->load('matcher_phpcr.xml');
        $loader->load('phpcr-sitemap.xml');
    }

    private function loadContentListener(array $config, XmlFileLoader $loader, ContainerBuilder $container)
    {
        $container->setParameter($this->getAlias() . '.content_key', $config['content_key']);

        $loader->load('content-listener.xml');

        $this->contentListenerEnabled = true;
    }

    /**
     * When setting a custom alternate locale provider with its id, this one will
     * be injected to the content listener.
     *
     * When using phpcr-odm a default provider will be set, when choosing no own one.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function loadAlternateLocaleProvider($config, ContainerBuilder $container)
    {
        $alternateLocaleProvider = null === $config['provider_id']
            ? $this->defaultAlternateLocaleProviderId
            : $config['provider_id'];

        if (!$alternateLocaleProvider) {
            throw new InvalidConfigurationException('Alternate locale provider enabled but none defined. You need to enable PHPCR or configure alternate_locale.provider_id');
        }

        if ($container->has($this->getAlias().'.event_listener.seo_content')) {
            $container
                ->findDefinition($this->getAlias().'.event_listener.seo_content')
                ->addMethodCall(
                    'setAlternateLocaleProvider',
                    array(new Reference($alternateLocaleProvider))
                )
            ;
        }

        if ($container->has($this->getAlias().'.sitemap.guesser.alternate_locales')) {
            $container
                ->findDefinition($this->getAlias().'.sitemap.guesser.alternate_locales')
                ->replaceArgument(0, new Reference($alternateLocaleProvider))
            ;
        }
    }

    /**
     * Enabled suggestion providers will stay in the container only.
     *
     * The providers are activated only, when phpcr is chosen as persistence.
     *
     * @param $config
     * @param ContainerBuilder $container
     */
    private function loadErrorHandling($config, ContainerBuilder $container)
    {
        foreach (array('parent', 'sibling') as $group) {
            $remove = isset($config['enable_'.$group.'_provider'])
                    && !$config['enable_'.$group.'_provider'] ? true : false;
            if ($container->has('cmf_seo.error.suggestion_provider.'.$group) && $remove) {
                $container->removeDefinition('cmf_seo.error.suggestion_provider.'.$group);
            }
        }
    }

    /**
     * @param array            $config
     * @param XmlFileLoader    $loader
     * @param ContainerBuilder $container
     * @param boolean          $alternateLocale Whether alternate locale handling is loaded.
     */
    private function loadSitemapHandling($config, XmlFileLoader $loader, ContainerBuilder $container, $alternateLocale)
    {
        $loader->load('sitemap.xml');

        $configurations = $config['configurations'];
        // if there are no explicit configurations, enable the default sitemap
        if (!count($configurations)) {
            $configurations['sitemap'] = array();
        }

        // set the default templates if not set
        if (!count($config['defaults']['templates'])) {
            $config['defaults']['templates'] = CmfSeoBundle::$defaultSitemapTemplates;
        }

        foreach ($configurations as $key => $configuration) {
            if (isset($configuration['default_change_frequency'])) {
                $definition = new Definition('%cmf_seo.sitemap.guesser.default_change_frequency.class%', array(
                    $configuration['default_change_frequency']
                ));
                $definition->addTag('cmf_seo.sitemap.guesser', array(
                    'sitemap' => $key,
                    'priority' => -1,
                ));
                $container->setDefinition($this->getAlias().'.sitemap.guesser.'.$key.'.default_change_frequency', $definition);
            }
            unset($configurations[$key]['default_change_frequency']);

            // copy default configuration into this sitemap configuration to keep controller simple
            foreach ($config['defaults']['templates'] as $format => $name) {
                if (!isset($configurations[$key]['templates'][$format])) {
                    $configurations[$key]['templates'][$format] = $name;
                }
            }
        }

        $container->setParameter($this->getAlias().'.sitemap.configurations', $configurations);

        $container->setParameter(
            $this->getAlias().'.sitemap.default_change_frequency',
            $config['defaults']['default_change_frequency']
        );

        if (!$alternateLocale) {
            $container->removeDefinition($this->getAlias().'.sitemap.guesser.alternate_locales');
        }
    }
}
