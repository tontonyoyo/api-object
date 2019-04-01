<?php

namespace TontonYoyo\ApiObjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use TontonYoyo\ApiObjectBundle\Service\ApiObjectManager;

class ApiObjectController extends AbstractController
{

    protected $aom;

    public function __construct(ApiObjectManager $aom)
    {
        $this->aom = $aom;
    }






}