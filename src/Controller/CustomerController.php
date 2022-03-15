<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\Type\CustomerType;
use App\Repository\CustomerRepository;
use App\Controller\AbstractApiController;
use App\Services\CustomerValidation;
use App\Services\FormValidation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractApiController
{
    private $customerValidation;
    private $formValidation;

    public function __construct(CustomerValidation $customerValidation, FormValidation $formValidation){

        $this->customerValidation = $customerValidation;
        $this->formValidation = $formValidation;
    }

    public function indexAction(CustomerRepository $customerRepository): Response {

        $customers = $customerRepository->findAll();
        return $this->respond($customers);
    }

    public function showAction(CustomerRepository $customerRepository, $customerId){

        $customer = $customerRepository->findOneBy([
            'id' => $customerId,
        ]);

        return $this->respond($customer);

    }

    public function deleteAction(Request $request, EntityManagerInterface $entityManager, CustomerRepository $customerRepository, $customerId){

        $customer = $customerRepository->findOneBy([
            'id' => $customerId,
        ]);

        $this->customerValidation->customerValidation($customer);

        $entityManager->remove($customer);
        $entityManager->flush();

        return $this->respond(null);
    }

    public function createAction(Request $request, EntityManagerInterface $entityManager){

        $form = $this->buildForm(CustomerType::class);
        $form->handleRequest($request);
//        if (!$form->isSubmitted() || !$form->isValid()){
//            return $this->respond($form, Response::HTTP_BAD_REQUEST);
//        }
        $this->formValidation->formValidation($form);

        $customer = $form->getData();
        $entityManager->persist($customer);
        $entityManager->flush();

        return $this->respond($customer);
    }
}
