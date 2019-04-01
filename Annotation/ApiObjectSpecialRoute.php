<?php

namespace TontonYoyo\ApiObjectBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
* @author YMR-ImplementsDumbFactory
 * @Annotation
 * @Annotation\Target("CLASS")
 */
class ApiObjectSpecialRoute
{
    /**
     * @var string
     */
    public $name ='string';

    /**
     * @var string
     */
    public $path ='string';

    /**
     * @Enum({"GET","POST","PUT","DELETE"})
     */
    public $method ='GET';

    /**
     * @Enum({"collection","object"})
     */
    public $operation = 'collection';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }



    /**
     * @return mixed
     */
    public function getOperation()
    {
        return $this->operation;
    }



}