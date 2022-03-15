<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerValidation
{
    public function customerValidation($customer) {
        if(!$customer){
            throw new NotFoundHttpException('Customer not found');
        }
    }
}