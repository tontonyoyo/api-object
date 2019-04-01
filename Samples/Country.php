<?php

namespace TontonYoyo\ApiObjectBundle\Samples;

use TontonYoyo\ApiObjectBundle\ApiObject\AbstractApiObject;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObject as AOM;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObjectField as AOMField;

/**
 * @AOM(
 *     name="Country",
 *     api_table_name="countries",
 *     use_yaml_schema=false,
 *     schema="Country.yml"
 *     )
 */
class Country extends AbstractApiObject
{

    /**
     * @AOMField()
     */
    private $id;

    /**
     * @AOMField()
     */
    private $shortLabel;

    /**
     * @AOMField(
     *     autocomplete=true
     * )
     */
    private $label;


    public function __toString():string
    {
        return (string)$this->label;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getShortLabel()
    {
        return $this->shortLabel;
    }

    public function setShortLabel($shortLabel)
    {
        $this->shortLabel = $shortLabel;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getApiRequestFields()
    {
        return [
            '$id',
            '$shortLabel',
            '$label',
        ];
    }


    public function getUnserializedData()
    {
        foreach($this->getApiRequestFields() as $param ){
            if(!is_null($this->$param)){
                $unserializedData[$param]=$this->$param;
            }
        }
        return $unserializedData;
    }

    public function getObjectVars()
    {
        return get_object_vars($this);
    }

}
