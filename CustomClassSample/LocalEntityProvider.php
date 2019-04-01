<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13/07/18
 * Time: 14:48
 */

namespace TontonYoyo\ApiObjectBundle\CustomClassSample;

use Doctrine\ORM\EntityManagerInterface;
use TontonYoyo\ApiObjectBundle\Factory\CustomProviderInterface;

class LocalEntityProvider implements CustomProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $className;

    /**
     * @var
     */
    private $findOneByParameter;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return mixed
     */
    public function getElement($apiValue)
    {
        $this->em->getRepository($this->className)
            ->findOneBy([
                $this->findOneByParameter => $apiValue
            ]);
    }

    /**
     * @return mixed
     */
    public function getApiValue($element)
    {

    }

    /**
     * @param $parameters
     * @return mixed
     */
    public function setParameters($parameters)
    {
        $this->className = $parameters['className'];
        $this->findOneByParameter = $parameters['findOneByParameter'];
    }
}