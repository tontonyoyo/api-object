<?php

namespace TontonYoyo\ApiObjectBundle\ParamConverter;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObjectDiscovery;
use TontonYoyo\ApiObjectBundle\Service\ApiObjectManagerInterface;

/**
 * Param converter for Object used by ApiObjectBundle
 * Class ApiObjectParamConverter
 * @package TontonYoyo\ApiObjectBundle\Request\ParamConverter
 */
class ApiObjectParamConverter implements ParamConverterInterface
{
    protected $aom;
    protected $discovery;

    /**
     * ApiObjectParamConverter constructor.
     * @param ApiObjectManagerInterface $aom
     * @param ApiObjectDiscovery $discovery
     */
    public function __construct(ApiObjectManagerInterface $aom,ApiObjectDiscovery $discovery)
    {
        $this->aom = $aom;
        $this->discovery = $discovery;
    }


    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool|void
     * @throws \Http\Client\Exception
     * @throws \ReflectionException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();
        $class = $configuration->getClass();


        $id = $request->attributes->get($name);

        $this->aom->unsetSpecialRoute()
            ->setup((new \ReflectionClass($class))->getShortName())
            ->findOneById($id);

        $object = $this->aom->getEntity();

        $request->attributes->set($name, $object);
    }


    /**
     * use ApiObject Discovery service to find if parameter an Api object
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        return $this->discovery->isSharedEntity($configuration->getClass());

    }


}