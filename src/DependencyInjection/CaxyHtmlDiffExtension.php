<?php

namespace Caxy\HtmlDiffBundle\DependencyInjection;

use Caxy\HtmlDiff\HtmlDiffConfig;
use Doctrine\Bundle\DoctrineCacheBundle\DependencyInjection\CacheProviderLoader;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CaxyHtmlDiffExtension extends Extension
{
    /**
     * @var null|CacheProviderLoader
     */
    private $cacheProviderLoader;

    /**
     * CaxyHtmlDiffExtension constructor.
     */
    public function __construct()
    {
        if (class_exists('Doctrine\\Bundle\\DoctrineCacheBundle\\DependencyInjection\\CacheProviderLoader')) {
            $this->cacheProviderLoader = new CacheProviderLoader();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $cacheDriverId = null;

        if (!empty($config['doctrine_cache_driver']) && $config['doctrine_cache_driver']['enabled']) {
            $cacheDriverId = $this->loadCacheDriver('doctrine_cache_driver', $config['doctrine_cache_driver'], $container);
        }

        if (!isset($config['purifier_cache_location'])) {
            $config['purifier_cache_location'] = $container->getParameter('kernel.cache_dir');
        }

        foreach ($config as $key => $value) {
            $container->setParameter($this->getAlias() . '.' . $key, $value);
        }

        $this->loadHtmlDiffConfig($config, $container, $cacheDriverId);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param string           $cacheDriverId
     */
    protected function loadHtmlDiffConfig(array $config, ContainerBuilder $container, $cacheDriverId = null): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $definition = $container->getDefinition('caxy.html_diff.config');

        $methodsToCall = array(
            'special_case_tags'       => 'setSpecialCaseTags',
            'encoding'                => 'setEncoding',
            'special_case_chars'      => 'setSpecialCaseChars',
            'group_diffs'             => 'setGroupDiffs',
            'insert_space_in_replace' => 'setInsertSpaceInReplace',
            'match_threshold'         => 'setMatchThreshold',
            'use_table_diffing'       => 'setUseTableDiffing',
            'purifier_cache_location' => 'setPurifierCacheLocation',
        );

        foreach ($methodsToCall as $key => $methodName) {
            if (array_key_exists($key, $config)) {
                $definition->addMethodCall($methodName, array($config[$key]));
            }
        }

        if (null !== $cacheDriverId) {
            $definition->addMethodCall('setCacheProvider', array(new Reference($cacheDriverId)));
        }
    }

    /**
     * @param string           $cacheName
     * @param array            $driverMap
     * @param ContainerBuilder $container
     *
     * @return string
     * @throws \Exception
     */
    protected function loadCacheDriver($cacheName, array $driverMap, ContainerBuilder $container): string
    {
        if (null === $this->cacheProviderLoader) {
            throw new \Exception('DoctrineCacheBundle required to use doctrine_cache_driver.');
        }

        $aliasId = $this->getAlias().'_'.$cacheName;

        if (!empty($driverMap['cache_provider'])) {
            $serviceId = sprintf('doctrine_cache.providers.%s', $driverMap['cache_provider']);
            $container->setAlias($aliasId, new Alias($serviceId, false));

            return $aliasId;
        }

        $id       = $aliasId;
        $host     = isset($driverMap['host']) ? $driverMap['host'] : null;
        $port     = isset($driverMap['port']) ? $driverMap['port'] : null;
        $password = isset($driverMap['password']) ? $driverMap['password'] : null;
        $database = isset($driverMap['database']) ? $driverMap['database'] : null;
        $type     = $driverMap['type'];

        if ($type == 'service') {
            $container->setAlias($id, new Alias($driverMap['id'], false));

            return $id;
        }

        $config = array(
            'aliases'   => array($id),
            $type       => array(),
            'type'      => $type,
            'namespace' => null,
        );

        if (!isset($driverMap['namespace'])) {
            // generate a unique namespace for the given application
            $environment = $container->getParameter('kernel.root_dir').$container->getParameter('kernel.environment');
            $hash        = hash('sha256', $environment);
            $namespace   = 'sf2' . $this->getAlias() . '_' . $hash;

            $driverMap['namespace'] = $namespace;
        }

        $config['namespace'] = $driverMap['namespace'];

        if (in_array($type, array('memcache', 'memcached'))) {
            $host = !empty($host) ? $host : 'localhost';
            $config[$type]['servers'][$host] = array(
                'host' => $host,
                'port' => !empty($port) ? $port : 11211,
            );
        }

        if ($type === 'redis') {
            $config[$type] = array(
                'host' => !empty($host) ? $host : 'localhost',
                'port' => !empty($port) ? $port : 6379,
                'password' => !empty($password) ? $password : null,
                'database' => !empty($database) ? $database : 0
            );
        }

        $this->cacheProviderLoader->loadCacheProvider($id, $config, $container);

        return $id;
    }
}
