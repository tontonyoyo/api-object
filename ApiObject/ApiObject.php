<?php

/**
* @author YMR-ImplementsDumbFactory
 *
 * ApiObject.php  Interface for entities compatible with ApiObjectManager
 *
 * - TODO
 * - versionning
 * - implements ANY method mandatory to shared entities
 */

namespace TontonYoyo\ApiObjectBundle\ApiObject;

interface ApiObject
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getLabel();

    /**
     * @param $label
     *
     */
    public function setLabel($label);


}