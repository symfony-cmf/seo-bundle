<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SeoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
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

    private $sitemapHelperMap = array(
        'loaders' => 'cmf_seo.sitemap.loader',
        'guessers' => 'cmf_seo.sitemap.guesser',
        'voters' => 'cmf_seo.sitemap.voter',
    );

    /**
     * {@inheritdoc}
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
            $container->setParameter('cmf_seo.backend_type_phpcr', true);
            $container->setParameter(
                'cmf_seo.persistence.phpcr.manager_name',
                $config['persistence']['phpcr']['manager_name']
            );
            $sonataBundles[] = 'SonataDoctrinePHPCRAdminBundle';

            $this->loadPhpcr($config['persistence']['phpcr'], $loader, $container);
        }

        if ($this->isConfigEnabled($container, $config['persistence']['orm'])) {
            $container->setParameter('cmf_seo.backend_type_orm', true);
            $container->setParameter(
                'cmf_seo.persistence.orm.manager_name',
                $config['persistence']['orm']['manager_name']
            );
            $sonataBundles[] = 'SonataDoctrineORMBundle';
        }

        $container->setParameter('cmf_seo.form_mode_orm',
            $this->isConfigEnabled($container, $config['persistence']['orm'])
            && !$this->isConfigEnabled($container, $config['persistence']['phpcr'])
        );

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

        $this->loadFormConfiguration(
            $config['form'],
            $container,
            $this->isConfigEnabled($container, $config['persistence']['phpcr']) ? 'phpcr' : 'default'
        );
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

        $container->setParameter('cmf_seo.sonata_admin_extension.form_group', $config['form_group']);
        $loader->load('admin.xml');
    }

    /**
     * Puts the seo parameters into the container.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function loadSeoParameters(array $config, ContainerBuilder $container)
    {
        $params = array('translation_domain', 'title', 'description', 'original_route_pattern');

        foreach ($params as $param) {
            $value = isset($config[$param]) ? $config[$param] : null;
            $container->setParameter('cmf_seo.'.$param, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return 'http://cmf.symfony.com/schema/dic/seo';
    }

    /**
     * {@inheritdoc}
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    /**
     * @param $config
     * @param XmlFileLoader    $loader
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
        $container->setParameter('cmf_seo.content_key', $config['content_key']);

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

        if ($container->has('cmf_seo.event_listener.seo_content')) {
            $container
                ->findDefinition('cmf_seo.event_listener.seo_content')
                ->addMethodCall(
                    'setAlternateLocaleProvider',
                    array(new Reference($alternateLocaleProvider))
                )
            ;
        }

        if ($container->has('cmf_seo.sitemap.guesser.alternate_locales')) {
            $container
                ->findDefinition('cmf_seo.sitemap.guesser.alternate_locales')
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

        $templates = isset($config['templates']) ? $config['templates'] : array();
        $exclusionRules = isset($config['exclusion_rules']) ? $config['exclusion_rules'] : array();
        $container->setParameter('cmf_seo.error.templates', $templates);

        $exclusionMatcherDefinition = $container->getDefinition('cmf_seo.error.exclusion_matcher');
        foreach ($exclusionRules as $rule) {
            $rule['host'] = !isset($rule['host']) ? null : $rule['host'];
            $rule['methods'] = !isset($rule['methods']) ? null : $rule['methods'];
            $rule['ips'] = !isset($rule['ips']) ? null : $rule['ips'];
            $requestMatcher = $this->createRequestMatcher(
                $container,
                $rule['path'],
                $rule['host'],
                $rule['methods'],
                $rule['ips']
            );
            $exclusionMatcherDefinition->addMethodCall('addRequestMatcher', array($requestMatcher));
        }
    }

    private function createRequestMatcher(ContainerBuilder $container, $path = null, $host = null, $methods = null, $ips = null, array $attributes = array())
    {
        $arguments = array($path, $host, $methods, $ips, $attributes);
        $serialized = serialize($arguments);
        $id = 'cmf_seo.error.request_matcher.'.md5($serialized).sha1($serialized);

        if (!$container->hasDefinition($id)) {
            $container
                ->setDefinition($id, new DefinitionDecorator('cmf_seo.error.request_matcher'))
                ->setArguments($arguments)
            ;
        }

        return new Reference($id);
    }

    /**
     * @param array            $config
     * @param XmlFileLoader    $loader
     * @param ContainerBuilder $container
     * @param bool             $alternateLocale Whether alternate locale handling is loaded.
     */
    private function loadSitemapHandling($config, XmlFileLoader $loader, ContainerBuilder $container, $alternateLocale)
    {
        $loader->load('sitemap.xml');

        $configurations = $config['configurations'];

        $helperStatus = array();
        foreach ($this->sitemapHelperMap as $helper => $tag) {
            $helperStatus[$helper] = array();
            $serviceDefinitionIds = $container->findTaggedServiceIds($tag);
            foreach ($serviceDefinitionIds as $id => $attributes) {
                if (0 === strpos($id, 'cmf_seo')) {
                    // avoid interfering with services that are not part of this bundle
                    $helperStatus[$helper][$id] = array();
                }
            }
        }

        foreach ($configurations as $sitemapName => $configuration) {
            if (isset($configuration['default_change_frequency'])) {
                $definition = new Definition('%cmf_seo.sitemap.guesser.default_change_frequency.class%', array(
                    $configuration['default_change_frequency'],
                ));
                $definition->addTag('cmf_seo.sitemap.guesser', array(
                    'sitemap' => $sitemapName,
                    'priority' => -1,
                ));
                $container->setDefinition('cmf_seo.sitemap.guesser.'.$sitemapName.'.default_change_frequency', $definition);
            }
            unset($configurations[$sitemapName]['default_change_frequency']);

            // copy default configuration into this sitemap configuration to keep controller simple
            foreach ($config['defaults']['templates'] as $format => $name) {
                if (!isset($configurations[$sitemapName]['templates'][$format])) {
                    $configurations[$sitemapName]['templates'][$format] = $name;
                }
            }
            foreach ($helperStatus as $helper => $map) {
                $status = count($configuration[$helper]) ? $configuration[$helper] : $config['defaults'][$helper];

                foreach ($status as $s) {
                    if ('_all' === $s) {
                        foreach ($helperStatus[$helper] as $id => $sitemaps) {
                            $helperStatus[$helper][$id][] = $sitemapName;
                        }
                    } elseif ('_none' !== $s) {
                        $helperStatus[$helper][$s][] = $sitemapName;
                    }
                }
                unset($configurations[$sitemapName][$helper]);
            }
        }

        $container->setParameter('cmf_seo.sitemap.configurations', $configurations);

        $container->setParameter(
            'cmf_seo.sitemap.default_change_frequency',
            $config['defaults']['default_change_frequency']
        );

        $this->handleSitemapHelper($helperStatus, $container);

        if (!$alternateLocale) {
            $container->removeDefinition('cmf_seo.sitemap.guesser.alternate_locales');
        }
    }

    /**
     * Each helper type out of the guessers, loaders and voters hav its on configuration to enable/disable them.
     *
     * @param array            $helperStatus Map of type => id => list of sitemaps
     * @param ContainerBuilder $container
     */
    private function handleSitemapHelper($helperStatus, ContainerBuilder $container)
    {
        foreach ($helperStatus as $type => $status) {
            foreach ($status as $id => $sitemaps) {
                if (count($sitemaps)) {
                    $definition = $container->getDefinition($id);
                    $tags = $definition->getTag($this->sitemapHelperMap[$type]);
                    $tag = reset($tags);
                    $tag['sitemap'] = implode(',', $sitemaps);
                    $definition->clearTag($this->sitemapHelperMap[$type]);
                    $definition->addTag($this->sitemapHelperMap[$type], $tag);
                } else {
                    $container->removeDefinition($id);
                }
            }
        }
    }

    /**
     * Configuration block to configure form.
     *
     * The data_class option form type for the SeoMetadata depends on the chose storage.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     * @param string           $storage   Information about the configured storage.
     */
    private function loadFormConfiguration($config, ContainerBuilder $container, $storage)
    {
        $seoMetadataClass = 'Symfony\Cmf\Bundle\SeoBundle\Model\SeoMetadata';
        if (null !== $config['data_class']['seo_metadata']) {
            $seoMetadataClass = $config['data_class']['seo_metadata'];
        } elseif ('phpcr' === $storage) {
            $seoMetadataClass = 'Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata';
        }

        $container->setParameter('cmf_seo.form.data_class.seo_metadata', $seoMetadataClass);
    }
}
