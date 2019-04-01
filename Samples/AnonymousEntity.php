<?php

namespace TontonYoyo\ApiObjectBundle\Samples;

use TontonYoyo\ApiObjectBundle\ApiObject\AbstractApiObject;

class AnonymousEntity extends AbstractApiObject
{
    private $id;

    private $label;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed
     */
    public function setId($id)
    {
        $this->id =$id;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param $label
     *
     */
    public function setLabel($label)
    {
        $this->label =$label;
    }
}