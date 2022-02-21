<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Repository\ProductRepository;
use App\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractApiController
{
    public function indexAction(Request $request): Response {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        // $allProducts = ProductRepository::class;
        // $allProducts->findAll();
        return $this->respond($products);
    }

    public function deleteAction(Request $request){
        $productId = $request->get('productId');
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy([
            'id' => $productId,
        ]);

        if(!$product){
            throw new NotFoundHttpException('Product not found');
        }

        $this->getDoctrine()->getManager()->remove($product);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond(null);

    }

    public function createAction(Request $request){

        $form = $this->buildForm(ProductType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()){
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $product = $form->getData();
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();
        
        return $this->respond($product);
    }
}
