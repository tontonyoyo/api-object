<?php

namespace TontonYoyo\ApiObjectBundle\ApiObject;

use TontonYoyo\ApiObjectBundle\Annotation\ApiObject as AOM;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObjectField as AOMField;

abstract class AbstractApiObject implements ApiObject
{
    /**
     * @AOMField(
     *     type="integer",
     *     type_out="integer"
     * )
     */
    protected $id;

    /**
     * @AOMField()
     */
    protected $label;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return AbstractApiObject
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     * @return AbstractApiObject
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }


}