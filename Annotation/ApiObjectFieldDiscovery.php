<?php


namespace TontonYoyo\ApiObjectBundle\Annotation;

use Doctrine\Common\Annotations\Reader;

class ApiObjectFieldDiscovery
{

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var string
     */
    private $classname =null;

    /**
     * @var array
     */
    private $properties_annotation = null;


    /**
     * @param Reader $annotationReader
     */
    public function __construct( Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param $classname
     * @return bool
     * @throws \ReflectionException
     */
    public function getApiObjectProperties($classname)
    {
        $this->classname = $classname;
        $this->discoverApiObjectProperties();

        if (empty($this->properties_annotation)) {
            return false;
        }
        return true;
    }


    /**
    * @author YMR-ImplementsDumbFactory
     * @throws \ReflectionException
     */
    public function discoverApiObjectProperties()
    {
        $classReflect = new \ReflectionClass($this->classname);
        $this->properties_annotation =[];

        foreach ($classReflect->getProperties() as $propertyReflect) {

            $property_annotation = $this->annotationReader->getPropertyAnnotation($propertyReflect, 'TontonYoyo\ApiObjectBundle\Annotation\ApiObjectField');
            if (!$property_annotation) {
                continue;
            }
            $this->properties_annotation[$propertyReflect->getName()] = $property_annotation;
        }
    }

    /**
    * @author YMR-ImplementsDumbFactory
     */
    public function getPropertiesAnnotations()
    {
        return $this->properties_annotation;
    }




}