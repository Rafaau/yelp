<?php

namespace App\Controller;

use App\Interface\BusinessServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BusinessController extends AbstractController
{
    private $businessInterface;

    public function __construct(BusinessServiceInterface $businessInterface)
    {
        $this->businessInterface = $businessInterface;
    }

    #[Route('/businesses', name: 'get-businesses' )]
    public function getAll(Request $request): Response {
        $cflt = $request->query->get('cflt');
        $find_loc = $request->query->get('find_loc');

        $businessesData = $this->businessInterface->getBusinesses($cflt, $find_loc);

        return new JsonResponse([
            'status' => 'ok', 
            'businesses' => $businessesData
        ]);
    }

    #[Route('/businesses/{business}-{location}', name: 'get-business' )]
    public function get($business, $location): Response {
        $businessData = $this->businessInterface->getBusiness($business, $location);

        return new JsonResponse([
            'status' => 'ok', 
            'business' => $businessData
        ]);
    }

    #[Route('/businesses/create', name: 'business-create' )]
    public function update(Request $request): Response {
        $data = json_decode($request->getContent(), true);
        
        $business = $this->businessInterface->createBusiness($data, $this->getUser()->getUserIdentifier());

        return new JsonResponse(['status' => $business ? 'ok' : 'error']);
    }
}
