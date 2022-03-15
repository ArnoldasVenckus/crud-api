<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductValidation
{
    public function productValidation($product) {
        if(!$product){
            throw new NotFoundHttpException('Product not found');
        }
    }
}