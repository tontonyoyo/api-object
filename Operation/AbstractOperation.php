<?php
/**
 * Created by PhpStorm.
 * User: ymir
 * Date: 21/02/19
 * Time: 16:31
 */

namespace TontonYoyo\ApiObjectBundle\Operation;

abstract class AbstractOperation implements OperationInterface
{
    private $name;

    private $method;

    private $url;

    private $prameters;


}