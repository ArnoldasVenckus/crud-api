<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use App\Controller\AbstractApiController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormValidation extends AbstractApiController
{
//    private $abstractApiController;
//
//    public function __construct(AbstractApiController $abstractApiController){
//        $this->abstractApiController = $abstractApiController;
//    }

    public function formValidation($form){
        if (!$form->isSubmitted() || !$form->isValid()){
            throw new NotFoundHttpException('Form Invalid');
//            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }
    }
}