<?php

/**
* @author YMR-ImplementsDumbFactory
 *
 */

namespace TontonYoyo\ApiObjectBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use TontonYoyo\ApiObjectBundle\Bridge\BridgeInterface;
use TontonYoyo\ApiObjectBundle\Factory\ApiObjectFactoryInterface;
use TontonYoyo\ApiObjectBundle\ApiObject\ApiObject;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObjectDiscovery;

interface ApiObjectManagerInterface
{
    public function __construct(BridgeInterface $bridge, ApiObjectDiscovery $discovery, ApiObjectFactoryInterface $depot, array $configuration);

    /**
     * Discover SharedEntities based on annotations or yaml settings
     * @return array
     */
    public function getSharedEntities();

    /**
    * @author YMR-ImplementsDumbFactory
     * transform annotations parameters to an array same as the yaml configuration array
     * @return array
     */
    public function getAnnotationConfig();

    /**
    * @author YMR-ImplementsDumbFactory
     * return a shared entity definition (FQCN + annots) by is name or throw exception if entity does'nt exist
     * @param $name
     * @return array
     * @throws \Exception
     */
    public function getSharedEntity(string $name);

    /**
    * @author YMR-ImplementsDumbFactory
     * Create a new object from the shared entities or exception if entity does'nt exists
     * @param $name
     * @return ApiObject
     * @throws \Exception
     */
    public function create(string $name);


    /**
     * get the bridge object, allow to get raw response instead of built entities
    * @author YMR-ImplementsDumbFactory
     * @return BridgeInterface
     */
    public function getBridge();

    /**
     * setup Depot and bridge based on provided entityName (just name not FQCN)
    * @author YMR-ImplementsDumbFactory
     * @param $entityName
     * @return $this
     */
    Public function setup(string $entityName);

    /**
     * Set special route and method
     * @author MIR Yoann -DGL -sword-group
     * @param string $routeName
     * @return mixed
     */
    public function setSpecialRoute(string $routeName);

    /**
     * Unset special route and method
     * @author MIR Yoann -DGL -sword-group
     * @return $this
     */
    public function unsetSpecialRoute();


    /**
    * @author YMR-ImplementsDumbFactory
     * @param ApiObject $entity
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function insert(ApiObject $entity);

    /**
    * @author YMR-ImplementsDumbFactory
     * @param ApiObject $entity
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function update(ApiObject $entity);

    /**
    * @author YMR-ImplementsDumbFactory
     * @param ApiObject $entity
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function delete(ApiObject $entity);

    /**
    * @author YMR-ImplementsDumbFactory
     * @param $id
     * @return ApiObject
     * @throws \Http\Client\Exception
     */
    public function findOneById($id);


    /**
     * @param $param
     * @return null
     * @throws \Http\Client\Exception
     */
    public function findOne($param);

    /**
     * * use bridge to find a collection of entities by search param and return full collection
    * @author YMR-ImplementsDumbFactory
     * @return ArrayCollection
     */
    public function findAll();

    /**
     * simple get request on final url
     * @author MIR Yoann -DGL -sword-group
     * @param array $params
     * @return ArrayCollection
     * @throws \Http\Client\Exception
     */
    public function searchWithFilters(array $params);

    /**
    * @author YMR-ImplementsDumbFactory
     * @param $param
     * @return $this
     */
    public function query($param);



    /**
    * @author YMR-ImplementsDumbFactory
     * @return mixed
     */
    public function getEntities();

    /**
    * @author YMR-ImplementsDumbFactory
     * @return ApiObject
     */
    public function getEntity();

    /**
    * @author YMR-ImplementsDumbFactory
     * @return ArrayCollection
     */
    public function getResponse();


    /**
     * @author MIR Yoann -DGL -sword-group
     * @return mixed
     */
    public function getStatusCode();


    /**
     * @author Malcolm Houel <malcolm.houel@sword-group.com>
     * @param int $depth
     * @return mixed
     */
    public function setDepotDepth(int $depth);


}