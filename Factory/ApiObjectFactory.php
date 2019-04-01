<?php

/**
* @author YMR-ImplementsDumbFactory
 * ApiObjectFactory.php
 */

namespace TontonYoyo\ApiObjectBundle\Factory;

use function class_exists;
use DateTimeZone;
use function in_array;
use Doctrine\Common\Collections\ArrayCollection;
use TontonYoyo\ApiObjectBundle\ApiObject\ApiObject;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObjectFieldDiscovery;
use function is_array;
use function is_string;
use Symfony\Component\Yaml\Yaml;

class ApiObjectFactory implements ApiObjectFactoryInterface
{
    protected $entityClass;

    protected $object;

    protected $collection;

    protected $data;

    protected $dataArray;

    protected $schema;

    protected $objects;

    /** @var ApiObjectFactoryInterface */
    protected $childFactory;

    protected $emptyFactory;

    protected $depth = null;

    protected $apiName;

    private $propertyDiscovery;

    /**
    * @author YMR-ImplementsDumbFactory
     * Factory constructor.
     * @param ApiObjectFieldDiscovery $propertyDiscovery
     */
    public function __construct(ApiObjectFieldDiscovery $propertyDiscovery)
    {
        $this->propertyDiscovery = $propertyDiscovery;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param $objects
     * @param $entityName
     * @param $depth
     * @param $apiName
     * @throws \ReflectionException
     */
    public function setup($objects,$entityName,$depth,$apiName)
    {
        if(!class_exists($objects[$entityName]['entity_class'])){
            return;
        }
        $this->object = null;
        $this->collection = new ArrayCollection();
        $this->data =[];
        $this->dataArray=[];
        $this->entityClass = $objects[$entityName]['entity_class'];
        $this->depth = $depth;
        $this->apiName = $apiName;
        $this->objects = $objects;
        $this->setSchema($entityName);
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param array $apiResponse
     * @throws \ReflectionException
     */
    public function processOneResponse(array $apiResponse)
    {
        $class = $this->entityClass;
        $this->object = new $class();
        $this->object =  $this->hydrate($this->object,$apiResponse);

    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param ApiObject $object
     * @throws \ReflectionException
     */
    public function processOneEntity(\TontonYoyo\ApiObjectBundle\ApiObject\ApiObject $object)
    {
        $this->object = $object;
        $this->data = $this->deshydrate($this->object);

    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param array $apiResponse
     * @throws \ReflectionException
     */
    public function processManyResponse(array $apiResponse)
    {
        foreach ($apiResponse as $index=>$data) {

            $class = $this->entityClass;
            $this->object = new $class();
            $this->hydrate($this->object,$data);
            $this->collection->add($this->object);
        }
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param ArrayCollection $collection
     * @throws \ReflectionException
     */
    public function processManyEntity(ArrayCollection $collection)
    {

        foreach ($collection as $index=>$object) {

            $this->dataArray[$index] = $this->deshydrate($object);

        }
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @return ArrayCollection
     */
    public function getcollection()
    {
        return $this->collection;
    }


    /**
    * @author YMR-ImplementsDumbFactory
     * @return array
     */
    public function getData()    {

        return $this->data;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @return array
     */
    public function getDataArray()
    {
        return $this->dataArray;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param ApiObject $object
     * @param array $data
     * @return mixed
     * @throws \ReflectionException
     */
    public function hydrate(ApiObject $object , array $data)
    {
        foreach($this->schema as $field=>$properties){

            if(isset($data[$field])){
                if(is_null($data[$field])){
                    continue;
                }
                $method ='set'.ucfirst($field);
                if(method_exists(get_class($this->object),$method)){

                    if (!is_null($properties['type'])) {

                        if('entities'== $properties['type']&& !is_string($data[$field])){

                            if(0 == $this->depth){
                                $this->object->$method(null);
                                continue;
                            }

                            $this->setChildFactory($properties['entity']);
                            $this->childFactory->processManyResponse($data[$field]);
                            $childObject = $this->childFactory->getcollection();
                            $this->object->$method($childObject);

                        }elseif('entity'== $properties['type']&& !is_string($data[$field])){

                            if(0 == $this->depth){
                                $this->object->$method(null);
                                continue;
                            }

                            $this->setChildFactory($properties['entity']);
                            $this->childFactory->processOneResponse($data[$field]);
                            $childObject = $this->childFactory->getObject();
                            $this->object->$method($childObject);
                        }elseif ('custom_provider'===$properties['type']){
                            $class = $properties['customClass'];

                            if(class_exists($class)
                                && in_array(CustomProviderInterface::class,class_implements($class))){

                                $provider = new $class();
                                $provider->setParameters($properties['parameters']);
                                $this->object->$method($provider->getElement($data[$field]));
                            }
                        }
                        elseif('datetime'== $properties['type']&&!empty($data[$field])){

                            if(is_array($data[$field])
                                &&isset($data[$field]['date'])
                                &&isset($data[$field]['timezone'])){
                                $datetimeValue = new \DateTime($data[$field]['date'],new DateTimeZone($data[$field]['timezone']));
                            }elseif(is_string($data[$field])){
                                $datetimeValue = new \DateTime($data[$field]);
                            }

                            if(isset($datetimeValue)&&false!=$datetimeValue){
                                $this->object->$method($datetimeValue);
                            }else{
                                $this->object->$method(null);
                            }
                        }
                        else{
                            $this->object->$method($data[$field]);
                        }
                    }else{
                        $this->object->$method($data[$field]);
                    }
                }
            }
        }
        return $this->object;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param ApiObject $object
     * @return mixed
     * @throws \ReflectionException
     */
    public function deshydrate(ApiObject $object)
    {
        $this->data = [];
        foreach ($this->schema as $field => $properties) {
            $method = 'get'.ucfirst($field);

            if (method_exists(get_class($object), $method)) {

                $value = $object->$method();

                if(is_null($value)){
                    /*
                     * GMT
                     * 14/05/2018
                     * 0097332: impossible de changer la civilité d'un contact BO/FO
                     * Mis en place car dans ce cas il n'y avait pas la variable dans le Post Data
                     * Changement fait pour avoir la variable avec la valeur nulle
                     *
                     * YMR except it won't work with insertion
                     * */
                    if('id' != $field){ // but that should ... :)
                        $this->data[$field] = null;
                    }
                    continue;
                }

                if (!is_null($properties['type_out'])) {

                    if ('iris' == $properties['type_out'] ){

                        $array = $this->setIris($object, $field,$this->objects[$properties['entity']]['api_table_name']);
                        $this->data[$field] = $array;

                    }elseif ('iri' == $properties['type_out'] ) {


                        if($value instanceof ApiObject){
                            $id = $value->getId();
                        }elseif(0 == $value){
                            continue;
                        }else{
                            $id =$value;
                        }

                        $this->data[$field] = $this->setIri($id,$this->objects[$properties['entity']]['api_table_name']);

                    }elseif ('entities' == $properties['type_out'] ) {

                        $this->setChildFactory($properties['entity']);
                        $this->childFactory->processManyEntity($value);
                        $value = $this->childFactory->getDataArray();
                        $this->data[$field] = $value;

                    } elseif ('entity' == $properties['type_out'] ) {

                        $this->setChildFactory($properties['entity']);
                        $this->childFactory->processOneEntity($value);
                        $value = $this->childFactory->getData();
                        $this->data[$field] = $value;

                    }
                    elseif ('custom_provider'===$properties['type_out']){
                        $class = $properties['customClass'];

                        if(class_exists($class)
                            && in_array(CustomProviderInterface::class,class_implements($class))){

                            $provider = new $class();
                            $provider->setParameters($properties['parameters']);
                            $this->object->$method($provider->getApiValue($value));
                        }
                    }
                    elseif ('datetime' == $properties['type_out'] ) {

                        if ($value instanceof \DateTime) {
                            $value = $value->format('Y-m-d h:i:s');
                        }
                        $this->data[$field] = $value;
                    }  elseif ('integer' == $properties['type_out'] ) {
                        $this->data[$field] = intval($value);

                    } else {
                        $this->data[$field] = $value;

                    }
                } else {
                    $this->data[$field] = $value;
                }
            }
        }
        return $this->data;
    }

    /**
     * User: nlefebvre
     * Date: 09/04/2018
     * Time: 14:55
     * @param $object
     * @param $name
     * @param $tableName
     * @return array
     */
    private function setIris($object, $name, $tableName)
    {
        $array = [];
        $method = 'get'.ucfirst($name);

        foreach ($object->$method() as $childObject) {
            if (!is_null($childObject->getId())) {
                $id = $this->setIri($childObject->getId(), $tableName);
                array_push($array, $id);
            } else {
                $array = [];
            }
        }
        return $array;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param mixed $id
     * @param string $tableName
     * @return mixed
     */
    private function setIri($id, $tableName)
    {

        return $this->isIri($id,$tableName) ?  $id : '/'.$this->apiName.'/'.$tableName.'/'.$id;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param mixed $id
     * @param string $tableName
     * @return boolean
     */
    public function isIri($id,$tableName)
    {
        return preg_match('/^(\/'.$this->apiName.'\/'.$tableName.'\/)/',$id);
    }


    /**
    * @author YMR-ImplementsDumbFactory
     * @param string $entityName
     * @return mixed|void
     * @throws \ReflectionException
     */
    public function setChildFactory(string $entityName)
    {
        $child_depth = $this->depth -1;
        $this->childFactory = clone $this;
        $this->childFactory->setup($this->objects,$entityName,$child_depth,$this->apiName);
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param string $entityName
     * @throws \ReflectionException
     */
    public function setSchema(string $entityName)
    {
        $schemaConfiguration = new SchemaConfiguration();
        /**
         * Setup schema from yaml file if option is yaml and file exists
         * Or annotations if annotations are in class
         * Or hydrate with default behaviour
         */
        if($this->objects[$entityName]['use_yaml_schema']
            && file_exists(__DIR__ . "/../Resources/schemas/" .$this->objects[$entityName]['schema'])){
            $schemaConfig = $this->getYml($this->objects[$entityName]['schema']);
            $this->schema = $schemaConfiguration->getSchemaFromYaml($schemaConfig);
        }elseif(false !== $this->propertyDiscovery->getSharedEntityProperties($this->objects[$entityName]['entity_class'])){
            $this->schema = $schemaConfiguration->getSchemaFromAnnot($this->propertyDiscovery->getPropertiesAnnotations());
        }else{
            $this->schema = null;
            return;
        }
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param string $part
     * @return mixed
     */
    public function getSchema($part ='fields')
    {
        return $this->schema[$part];
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param string $file
     * @return mixed
     */
    public function getYml(string $file)
    {
        $content = file_get_contents(__DIR__ . "/../Resources/schemas/" .$file);
        $yml = new Yaml();
        $schema = $yml->parse($content);
        return $schema;
    }

    /**
     * @param int $depth
     * @return mixed
     */
    public function setDepth(int $depth)
    {
        $this->depth = $depth;
    }


}