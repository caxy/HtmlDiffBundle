<?php

namespace Caxy\HtmlDiffBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('caxy_html_diff');

        $rootNode
            ->children()
                ->arrayNode('special_case_tags')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('encoding')->end()
                ->arrayNode('special_case_chars')
                    ->prototype('scalar')->end()
                ->end()
                ->booleanNode('group_diffs')->end()
                ->booleanNode('insert_space_in_replace')
                    ->defaultFalse()
                ->end()
                ->booleanNode('use_table_diffing')
                    ->defaultTrue()
                ->end()
                ->integerNode('match_threshold')->end()
                ->append($this->getDoctrineCacheDriverNode('doctrine_cache_driver'))
            ->end();

        return $treeBuilder;
    }

    /**
     * @param string $name
     *
     * @return ArrayNodeDefinition
     */
    private function getDoctrineCacheDriverNode($name)
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);
        $node
            ->canBeEnabled()
            ->beforeNormalization()
                ->ifString()
                ->then(function ($v) { return array('type' => $v); })
            ->end()
            ->children()
                ->scalarNode('type')->defaultValue('array')->end()
                ->scalarNode('host')->end()
                ->scalarNode('port')->end()
                ->scalarNode('instance_class')->end()
                ->scalarNode('class')->end()
                ->scalarNode('id')->end()
                ->scalarNode('namespace')->defaultNull()->end()
                ->scalarNode('cache_provider')->defaultNull()->end()
            ->end()
        ;

        return $node;
    }
}
