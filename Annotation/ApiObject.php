<?php

namespace TontonYoyo\ApiObjectBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
* @author YMR-ImplementsDumbFactory
 * @Annotation
 * @Annotation\Target("CLASS")
 */
class ApiObject
{
    public $name;

    public $depth =2;

    public $api_table_name;

    public $use_yaml_schema=false;

    public $schema =null;

    public $factory_class = "TontonYoyo\ApiObjectBundle\Factory\ApiObjectFactory";

    public $autocomplete =false;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getApiTableName()
    {
        return $this->api_table_name;
    }

    /**
     * @return mixed
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    public function getFactoryClass()
    {
        return $this->factory_class;
    }

    /**
     * @return bool
     */
    public function getAutocomplete()
    {
        return $this->autocomplete;
    }


    /**
     * @return bool
     */
    public function getUseYamlSchema()
    {
        return $this->use_yaml_schema;
    }

    /**
     * @return mixed
     */
    public function getDepth()
    {
        return $this->depth;
    }




}