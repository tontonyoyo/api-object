<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13/07/18
 * Time: 14:45
 */

namespace TontonYoyo\ApiObjectBundle\Factory;


interface CustomProviderInterface
{
    /**
     * @param $parameters
     * @return mixed
     */
    public function setParameters($parameters);

    /**
     * @param $apiValue
     * @return mixed
     */
    public function getElement($apiValue);

    /**
     * @param $element
     * @return mixed
     */
    public function getApiValue($element);



}