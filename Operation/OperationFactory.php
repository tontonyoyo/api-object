<?php
/**
 * Created by PhpStorm.
 * User: ymir
 * Date: 21/02/19
 * Time: 16:28
 */

namespace TontonYoyo\ApiObjectBundle\Operation;

class OperationFactory implements OperationFactoryInterface
{
    private const SUPPORTED_OPERATIONS =[
        self::OPERATION_PERSIST,
        self::OPERATION_UPDATE,
        self::OPERATION_REMOVE,
        self::OPERATION_SPECIAL,
    ];

    private const OPERATION_PERSIST = "persist";
    private const OPERATION_UPDATE = "update";
    private const OPERATION_REMOVE = "persist";
    private const OPERATION_SPECIAL = "persist";

    private static function supports(string $operation):void
    {
        if(!in_array($operation,self::SUPPORTED_OPERATIONS)){
            throw new \Exception("Operation ".$operation." is not supported ");
        }
    }

}