<?php
/**
* @author YMR-ImplementsDumbFactory
 *
 * SharedEntity annotation class
 */
namespace TontonYoyo\ApiObjectBundle\Factory;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

class SchemaConfiguration implements ConfigurationInterface
{
    /**
    * @author YMR-ImplementsDumbFactory
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('schema');
        $rootNode
            ->children()
                ->arrayNode('fields')
                    ->arrayPrototype()
                        ->children()
                            ->enumNode('type')
                                ->values(['iris','iri','entity','entities','datetime','string','integer','boolean','custom_class'])
                                ->defaultNull()
                            ->end()
                            ->enumNode('type_out')
                                ->values(['iris','iri','entity','entities','datetime','string','integer','boolean','custom_class'])
                                ->defaultNull()
                            ->end()
                            ->scalarNode('entity')->defaultNull()->end()
                            ->scalarNode('customClass')->defaultNull()->end()
                            ->arrayNode('parameters')
                                ->useAttributeAsKey('name')
                                ->scalarPrototype()->end()
                            ->end()
                            ->booleanNode('nullable')->defaultFalse()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @author YMR
     * @param $schemaConfig
     * @return mixed
     */
    public function getSchemaFromYaml($schemaConfig){

        $configuration = new SchemaConfiguration();
        $processor = new Processor();

        $processed = $processor->processConfiguration($configuration, $schemaConfig);
        $schema = $processed['fields'];
        return $schema ;
    }

    /**
     * @author YMR
     * @param $annots
     * @return array
     */
    public function getSchemaFromAnnot($annots){

        $schema =[];

        foreach($annots as $field =>$property_annotations){
            foreach(get_object_vars($property_annotations) as $var =>$value){
                $schema[$field][$var] = $value;
            }
        }
        return $schema;
    }



}