<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\Type\CustomerType;
use App\Repository\CustomerRepository;
use App\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractApiController
{
    public function indexAction(Request $request): Response {
        $customers = $this->getDoctrine()->getRepository(Customer::class)->findAll();
        return $this->respond($customers);
    }

    public function deleteAction(Request $request){
        $customerId = $request->get('customerId');
        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy([
            'id' => $customerId,
        ]);

        $this->getDoctrine()->getManager()->remove($customer);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond(null);
    }

    public function createAction(Request $request){

        $form = $this->buildForm(CustomerType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()){
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $customer = $form->getData();
        $this->getDoctrine()->getManager()->persist($customer);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($customer);
    }
}
