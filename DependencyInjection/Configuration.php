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
                ->scalarNode('base_url')
                    ->cannotBeEmpty()
                    ->info('Grand ID API base url, e.g. https://client-test.grandid.com/json1.1/')
                ->end()
                ->scalarNode('api_key')
                    ->cannotBeEmpty()
                    ->info('Grand ID API access key')
                ->end()
                ->scalarNode('authenticate_service_key')
                    ->cannotBeEmpty()
                    ->info('Grand ID API authentication service key')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}