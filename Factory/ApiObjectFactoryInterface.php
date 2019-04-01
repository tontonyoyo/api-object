<?php

/**
* @author YMR-ImplementsDumbFactory
 *
 * YMRFactoryInterface.php
 *
 * Interface for depot usable by sharedentitymanager.php
 *
 * - TODO
 * - versioning
 * - implements ANY method mandatory to Depot classes
 *
 *
 */

namespace TontonYoyo\ApiObjectBundle\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use TontonYoyo\ApiObjectBundle\ApiObject\ApiObject;

interface ApiObjectFactoryInterface
{

    /**
     * @param array
     * @return void
     */
    public function processOneResponse(array $apiResponse);

    /**
     * @param array
     * @return void
     */
    public function processManyResponse(array $apiResponse);

    /**
     * @param ApiObject
     * @return void
     */
    public function processOneEntity(ApiObject $object);

    /**
     * @param ArrayCollection
     * @return void
     */
    public function processManyEntity(ArrayCollection $collection);

    /**
     * @return mixed
     */
    public function getObject();


    /**
     * @return ArrayCollection
     */
    public function getCollection();

    /**
     * @return array
     */
    public function getData();

    /**
     * @return array
     */
    public function getDataArray();

    /**
     * @param string $entityName
     * @return mixed
     */
    public function setChildFactory(string $entityName);

    /**
     * @param string $entityName
     * @return mixed
     */
    public function setSchema(string $entityName);

    /**
     * @param string $file
     * @return mixed
     */
    public function getYml(string $file);

    /**
     * @param int $depth
     * @return mixed
     */
    public function setDepth(int $depth);

    /**
     * @param ApiObject $object
     * @param array $data
     * @return mixed
     */
    public function hydrate(ApiObject $object , array $data);

    /**
     * @param ApiObject $object
     * @return mixed
     */
    public function deshydrate(ApiObject $object);
}