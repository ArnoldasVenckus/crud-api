<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractFOSRestController
{
    protected function buildForm($type, $data = null, $options = []){

        return $this->container->get('form.factory')->createNamed('', $type, $data, $options);
    }

    protected function respond($data, $statusCode = Response::HTTP_OK){
        return $this->handleView($this->view($data, $statusCode));
    }
}
