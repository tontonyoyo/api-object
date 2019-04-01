<?php


namespace TontonYoyo\ApiObjectBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
    * @author YMR-ImplementsDumbFactory
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('ymr_api_object');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('ymr_api_object');
        }

        $rootNode
            ->children()
                ->scalarNode('api_url')->isRequired()->end()
                ->scalarNode('api_port')->defaultNull()->end()
                ->scalarNode('api_name')->defaultNull()->end()
                ->scalarNode('content_type')->defaultValue('Content-Type: application/json')->end()
                ->scalarNode('auth_content')->defaultNull()->end()
                ->enumNode('configuration')->values(['yaml','annotation'])->defaultValue('yaml')->end()
                ->arrayNode('entities')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('entity_class')->isRequired()->end()
                            ->scalarNode('api_table_name')->isRequired()->end()
                            ->arrayNode('specials_routes')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('name')->isRequired()->end()
                                        ->scalarNode('path')->isRequired()->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->booleanNode('use_yaml_schema')->defaultFalse()->end()
                            ->scalarNode('schema')->defaultNull()->end()
                            ->scalarNode('depth')->defaultValue(1)->end()
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
