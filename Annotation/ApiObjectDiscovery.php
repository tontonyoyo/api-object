<?php
/**
* @author YMR-ImplementsDumbFactory
 * Discovery class for class annotated with sharedentity annotation
 *
 */

namespace TontonYoyo\ApiObjectBundle\Annotation;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ApiObjectDiscovery
{
    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var array
     */
    private $apiObjects = [];


    /**
     * @param $namespace
     *   The namespace of the shared entities
     * @param $directory
     *   The directory of the shared entities
     * @param $rootDir
     * @param Reader $annotationReader
     */
    public function __construct(ParameterBagInterface $parameters, Reader $annotationReader)
    {
        $this->parameters = $parameters;
        $this->annotationReader = $annotationReader;
    }

    /**
     * @return array
     */
    public function getApiObjects()
    {
        if (!$this->apiObjects) {
            $this->discoverApiObjects();
        }
        return $this->apiObjects;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     */
    private function discoverApiObjects()
    {
        $path = $this->parameters->get('kernel.root_dir') . '/../' . $this->parameters->get('shared_entity_directory');
        $finder = new Finder();
        $finder->files()->in($path);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $class = $this->parameters->get('shared_entity_namespace') . '\\' . $file->getBasename('.php');
            $classReflect = new \ReflectionClass($class);

            $annotations = $this->annotationReader->getClassAnnotations($classReflect);

            if (!$annotations) {
                continue;
            }

            $annotation = $this->getClassAnnotation($annotations, 'TontonYoyo\ApiObjectBundle\Annotation\ApiObject');
            $special = $this->getManyClassAnnotation($annotations, 'TontonYoyo\ApiObjectBundle\Annotation\ApiObjectSpecialRoute');


            $this->apiObjects[$annotation->getName()] = [
                'class' => $class,
                'annotation' => $annotation,
                'special_routes'=>$special,
            ];
        }
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * Get one specific annotation in a class
     */
    public function getClassAnnotation($annotations, $annotationName)
    {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof $annotationName) {
                return $annotation;
            }
        }
        return;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * Get Many specific annotations in a class
     */
    public function getManyClassAnnotation($annotations, $annotationName)
    {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof $annotationName) {
                $selectedAnnotations[]= $annotation;
            }
        }
        if(empty($selectedAnnotations)){
            return;
        }
        return $selectedAnnotations;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * transform annotation objects in a configuration array
     * @param array $sharedEntitiesAnnots
     * @return array
     */
    public function transform(array $apiObjectsAnnotations)
    {
        $entities =[];

        foreach($apiObjectsAnnotations as $entity => $annotations){
            $entities[$entity]['entity_class'] = $annotations['class'];
            foreach(get_object_vars($annotations['annotation']) as $annot=>$value){
                $entities[$entity][$annot] =$value;
            }

            $routes =[];

            if(isset($annotations['special_routes'])){
                foreach($annotations['special_routes'] as $route){
                    if($route instanceof ApiObjectSpecialRoute){
                        $routes[$route->getName()]['path'] = $route->getPath();
                        $routes[$route->getName()]['method'] = $route->getMethod();
                        $routes[$route->getName()]['operation'] = $route->getOperation();
                    }
                }
            }
            $entities[$entity]['special_routes'] =$routes;
        }
        return $entities;
    }

    /**
     * @param string $className
     * @return bool
     */
    public function isSharedEntity(string $className)
    {
        if (!$this->apiObjects) {
            $this->discoverApiObjects();
        }
        foreach($this->apiObjects as $apiObject){
            if($className===$apiObject['class']){
                return true;
            }
        }
        return false;
    }


}