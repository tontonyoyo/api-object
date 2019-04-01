<?php

/**
* @author YMR-ImplementsDumbFactory
 *
 *
 */

namespace TontonYoyo\ApiObjectBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
* @author YMR-ImplementsDumbFactory *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ApiObjectExtension extends Extension
{
    /**
    * @author YMR-ImplementsDumbFactory
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $entityManagerDefinition = $container->getDefinition( 'TontonYoyo\ApiObjectBundle\Service\ApiObjectManager' );
        $entityManagerDefinition->addArgument($config);


    }


}
