<?php

namespace Bsadnu\GrandIDBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('grand_id');

        $rootNode
            ->children()
            ->scalarNode('base_url')->end()
            ->scalarNode('api_key')->end()
            ->scalarNode('authenticate_service_key')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}