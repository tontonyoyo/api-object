<?php


namespace TontonYoyo\ApiObjectBundle\Samples;

use TontonYoyo\ApiObjectBundle\ApiObject\AbstractApiObject;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObject as AOM;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObjectField as AOMField;

/**
 * @AOM(
 *     name="City",
 *     api_table_name="cities",
 *     use_yaml_schema=false,
 *     schema="City.yml"
 *     )
 */
class City extends AbstractApiObject
{
    /**
     * @AOMField()
     */
    private $id;

    /**
     * @AOMField()
     */
    private $dept;

    /**
     * @AOMField(
     *     autocomplete=true
     * )
     * @var string
     */
    private $label;

    /**
     * @AOMField()
     */
    private $zipCode;




    public function setId($id)
    {
        $this->id =$id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getDept()
    {
        return $this->dept;
    }

    public function setDept($dept)
    {
        $this->dept = $dept;
    }


    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    public function getApiRequestFields()
    {
        foreach($this as $attribute =>$value){
            if( method_exists($this,'set'.ucfirst($attribute))){
                $attributes[]= $attribute;
            }
        }

        $unsetables= [

        ];

        foreach($unsetables as $index){
            unset($attributes[$index]);
        }
        return $attributes;

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

    public function __toString()
    {
        return $this->label;
    }
}