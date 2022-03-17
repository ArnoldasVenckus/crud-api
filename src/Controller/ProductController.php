<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Repository\ProductRepository;
use App\Controller\AbstractApiController;
use App\Services\ProductValidation;
use App\Services\FormValidation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductController extends AbstractApiController
{
    private $productValidation;
    private $formValidation;

    public function __construct(ProductValidation $productValidation, FormValidation $formValidation)
    {
        $this->productValidation = $productValidation;
        $this->formValidation = $formValidation;
    }

    public function indexAction(Request $request, ProductRepository $repository): Response {

        $products = $repository->findAll();
        return $this->respond($products);
    }

    public function showAction(Request $request, ProductRepository $repository, $productId){

        $product = $repository->findOneBy([
            'id' => $productId,
        ]);

        $this->productValidation->productValidation($product);

        return $this->respond($product);
    }



    public function deleteAction(EntityManagerInterface $entityManager, ProductRepository $repository, $productId){

        $product = $repository->findOneBy([
            'id' => $productId,
        ]);

        $this->productValidation->productValidation($product);
//        if(!$product){
//            throw new NotFoundHttpException('Product not found');
//        }
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->respond(null);

    }

    public function createAction(Request $request, EntityManagerInterface $entityManager){

        $form = $this->buildForm(ProductType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()){
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $product = $form->getData();
        $entityManager->persist($product);
        $entityManager->flush();
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $product = $serializer->serialize($product, 'xml');
        
        return $this->respond($product);
    }

    public function updateAction(Request $request, ProductRepository $repository, EntityManagerInterface $entityManager, $productId){
        $product = $repository->findOneBy([
            'id' => $productId,
        ]);

        $this->productValidation->productValidation($product);

        $form = $this->buildForm(ProductType::class, $product, [
            'method' => $request->getMethod(),
        ]);
        $form->handleRequest($request);

        $this->formValidation->formValidation($form);
//        if (!$form->isSubmitted() || !$form->isValid()){
//            return $this->respond($form, Response::HTTP_BAD_REQUEST);
//        }

        $product = $form->getData();
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->respond($product);
    }

}
