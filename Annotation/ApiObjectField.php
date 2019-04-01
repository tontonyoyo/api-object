<?php


namespace TontonYoyo\ApiObjectBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @author YMR
 * @Annotation
 * @Annotation\Target("PROPERTY")
 */
class ApiObjectField
{
    /**
     * @Enum({"iri","iris","string","integer","datetime","datetimepicker","entity","entities","boolean","custom_provider"})
     */
    public $type =null;

    /**
     * @Enum({"iri","iris","string","integer","datetime","datetimepicker","entity","entities","boolean","custom_provider"})
     */
    public $type_out =null;

    /**
     * @var mixed
     */
    public $entity = null;

    /**
     * @var null
     */
    public $customClass = null;

    /**
     * @var array
     */
    public $parameters = [];


    /**
     * @var boolean
     */
    public $autocomplete = false;


    /**
     * @var boolean
     */
    public $nullable = true;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getTypeOut()
    {
        return $this->type_out;
    }

    /**
     * @return null
     */
    public function getEntity()
    {
        return $this->entity;
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
    public function getNullable()
    {
        return $this->nullable;
    }





}