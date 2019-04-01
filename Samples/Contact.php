<?php

namespace TontonYoyo\ApiObjectBundle\Samples;

use TontonYoyo\ApiObjectBundle\ApiObject\AbstractApiObject;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObject as AOM;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObjectField as AOMField;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObjectSpecialRoute as AOMSpecialRoute;


/**
 * @AOM(
 *     name="Contact",
 *     api_table_name="contacts",
 *     use_yaml_schema=false,
 *     schema="Contact.yml",
 *     autocomplete=true,
 *     depth="3"
 *     )
 * @AOMSpecialRoute(name="autocomplete",path="contacts/autocomplete",method="GET")
 */
class Contact extends AbstractApiObject
{
    /**
     * @AOMField(
     *   type = "integer",
     *   type_out = "integer"
     * )
     */
    private $id;

    /**
     * @AOMField(
     *     type="string",
     *     type_out ="string"
     * )
     */
    private $civility;

    /**
     * @AOMField(
     *     type="string",
     *     type_out="string",
     *     autocomplete=true
     * )
     */
    private $firstName;

    /**
     * @AOMField(
     *     type="string",
     *     type_out="string",
     *     autocomplete=true
     * )
     */
    private $lastName;

    /**
     * @AOMField(
     *     type="string",
     *     type_out="string",
     *     autocomplete=true
     * )
     */
    private $email;

    /**
     * @AOMField(
     *   type = "entity",
     *   type_out = "entity",
     *   entity="Address"
     * )
     */
    private $address = false;

    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->lastName." ".$this->firstName;
    }

}
