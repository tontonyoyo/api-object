<?php

/**
* @author YMR-ImplementsDumbFactory
 *
 */

namespace TontonYoyo\ApiObjectBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObjectDiscovery;
use TontonYoyo\ApiObjectBundle\Bridge\BridgeInterface;
use TontonYoyo\ApiObjectBundle\Factory\ApiObjectFactoryInterface;
use TontonYoyo\ApiObjectBundle\ApiObject\ApiObject;


class ApiObjectManager implements ApiObjectManagerInterface
{
    protected $httpHeader= [];

    protected $postData;

    protected $response;

    protected $serializer;

    protected $bridge;

    protected $depot;

    protected $entities;

    protected $selectedEntity;

    protected $sharedEntities;

    protected $discovery;

    public function __construct(BridgeInterface $bridge, ApiObjectDiscovery $discovery, ApiObjectFactoryInterface $depot,  $configuration)
    {
        $this->bridge = $bridge;
        $this->discovery = $discovery;
        $this->depot = $depot;
        $this->configure($configuration);
    }

    /**
     * configure the bridge from yaml and annotations setup
    * @author YMR-ImplementsDumbFactory
     * @param array $configuration
     */
    protected function configure(array $configuration)
    {
        $this->bridge->setApiPort($configuration['api_port']);
        $this->bridge->setApiUrl($configuration['api_url']);
        $this->bridge->setApiName($configuration['api_name']);
        $this->httpHeader= [
            'Content-Type'  =>$configuration['content_type'],
        ];
        $this->bridge->setHeader($this->httpHeader);
        if('yaml'===$configuration['configuration'] && isset($configuration['entities']) ){
            $this->entities = $configuration['entities'];
        }else{
            $this->entities = $this->getAnnotationConfig();
        }
        $this->bridge->buildUrl();
    }

    
    /**
     * Discover SharedEntities based on annotations or yaml settings
     * @return array
     */
    public function getSharedEntities()
    {
        return $this->discovery->getApiObjects();
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * transform annotations parameters to an array same as the yaml configuration array
     * @return array
     */
    public function getAnnotationConfig()
    {
        return  $this->discovery->transform($this->discovery->getApiObjects());
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * return a shared entity definition (FQCN + annots) by is name or throw exception if entity does'nt exist
     * @param $name
     * @return array
     * @throws \Exception
     */
    public function getSharedEntity(string $name) {
        $sharedEntities = $this->discovery->getApiObjects();
        if (isset($sharedEntities[$name])) {
            return $sharedEntities[$name];
        }

        throw new \Exception('SharedEntity '.$name.' not found.');
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * Create a new object from the shared entities or exception if entity does'nt exists
     * @param $name
     * @return ApiObject
     * @throws \Exception
     */
    public function create(string $name) {
        $workers = $this->discovery->getApiObjects();
        if (array_key_exists($name, $workers)) {
            $class = $workers[$name]['class'];
            if (!class_exists($class)) {
                throw new \Exception('Worker class does not exist.');
            }
            return new $class();
        }

        throw new \Exception('Worker does not exist.');
    }


    /**
     * get the bridge object, allow to get raw response instead of built entities
    * @author YMR-ImplementsDumbFactory
     * @return BridgeInterface
     */
    public function getBridge()
    {
        return $this->bridge;
    }

    /**
     * Get the configured depot
    * @author YMR-ImplementsDumbFactory
     * @return mixed
     */
    protected function getDepot()
    {
        return $this->depot;
    }

    /**
     * setup Depot and bridge based on provided entityName (just name not FQCN)
    * @author YMR-ImplementsDumbFactory
     * @param $entityName
     * @return $this
     */
    Public function setup(string $entityName)
    {
        $this->selectedEntity = $entityName;
        //TODO Voir si on peut passer la profondeur en variable pour surcharger la valeur par défaut
        $depth = $this->entities[$entityName]['depth'];
        $this->depot->setup($this->entities,$entityName,$depth,$this->bridge->getApiName());
        $this->bridge->setTableName($this->entities[$entityName]['api_table_name']);
        return $this;
    }

    /**
     * @author Malcolm Houel <malcolm.houel@sword-group.com>
     * @param int $depth
     * @return mixed
     */
    public function setDepotDepth(int $depth) {
        $this->depot->setDepth($depth);
        return $this;
    }

    /**
     * Set special route and method
     * @author MIR Yoann -DGL -sword-group
     * @param string $routeName
     * @return $this
     */
    public function setSpecialRoute(string $routeName)
    {

        if(is_null($this->entities[$this->selectedEntity]['special_routes'])){
            // TODO exception no special routes defined for this entity
            return $this;
        }

        foreach($this->entities[$this->selectedEntity]['special_routes'] as $name => $specialroute){

            if($routeName === $name){

                $this->bridge->setSpecialRoute($specialroute['path'],$specialroute['method']);
                return $this;
            }
        }
        return $this;
    }

    /**
     * Unset special route and method
     * @author MIR Yoann -DGL -sword-group
     * @return $this
     */
    public function unsetSpecialRoute()
    {
        $this->bridge->unsetSpecialRoute();
        return $this;
    }



    /**
    * @author YMR-ImplementsDumbFactory
     * @param ApiObject $entity
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function insert(ApiObject $entity)
    {
        $this->depot->processOneEntity($entity);
        $this->bridge->setPostData($this->depot->getData());
        $this->bridge->insert();
        return $this->getStatusCode();

    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param ApiObject $entity
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function update(ApiObject $entity)
    {
        $this->depot->processOneEntity($entity);
        $this->bridge->setPostData($this->depot->getData());
        $this->bridge->update($entity->getId());
        return $this->getStatusCode();

    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param ApiObject $entity
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function delete(ApiObject $entity)
    {
        $this->bridge->delete($entity);
        return $this->getStatusCode();
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param $id
     * @return ApiObject
     * @throws \Http\Client\Exception
     */
    public function findOneById($id)
    {
        $this->bridge->findOneById($id);
        return $this->getEntity();
    }


    /**
     * @param $param
     * @return null
     * @throws \Http\Client\Exception
     */
    public function findOne($param)
    {
        $this->bridge->find($param);
        return is_null($this->getEntities()) ? null: $this->getEntities()->first();
    }

    /**
     * @author YMR
     * @return ArrayCollection|mixed
     * @throws \Http\Client\Exception
     */
    public function findAll()
    {
        $this->bridge->findAll();
        return $this->getEntities();
    }

    /**
     * simple get request on final url
     * @author MIR Yoann -DGL -sword-group
     * @param $params
     * @return ArrayCollection
     * @throws \Http\Client\Exception
     */
    public function searchWithFilters(array $params)
    {
        $this->getBridge()->searchWithFilters($params);
        return $this->getEntities();
    }

    /**
     * @author YMR
     * @param $param
     * @return $this|SharedEntityManagerInterface
     * @throws \Http\Client\Exception
     */
    public function query($param)
    {
        $this->bridge->query($param);
        return $this;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @return mixed
     */
    public function getEntities()
    {
        $decodedResponse = $this->bridge->getDecodedResponse();

        if(!is_array($decodedResponse)||empty($decodedResponse)){
            return null;
        }

        $this->depot->processManyResponse($decodedResponse);
        return $this->depot->getCollection();
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @return ApiObject
     */
    public function getEntity()
    {
        $decodedResponse = $this->bridge->getDecodedResponse();

        if(!is_array($decodedResponse)||empty($decodedResponse)){
            return null;
        }
        $this->depot->processOneResponse($decodedResponse);
        return $this->depot->getObject();
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @return ArrayCollection
     */
    public function getResponse()
    {
        return $this->bridge->getResponse();
    }


    /**
     * @author MIR Yoann -DGL -sword-group
     * @return mixed
     */
    public function getStatusCode()
    {
        return  $this->bridge->getStatusCode();

    }




}