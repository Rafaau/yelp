<?php

namespace App\Controller;

use App\Interface\BusinessServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class BusinessController extends AbstractController
{
    private $businessInterface;

    public function __construct(BusinessServiceInterface $businessInterface)
    {
        $this->businessInterface = $businessInterface;
    }

    #[Route('/businesses', name: 'get-businesses' )]
    public function getAll(Request $request): Response {
        try {
            $cflt = $request->query->get('cflt');
            $find_loc = $request->query->get('find_loc');
    
            $businessesData = $this->businessInterface->getBusinesses($cflt, $find_loc);
    
            return new JsonResponse([
                'status' => 'ok', 
                'businesses' => $businessesData
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    #[Route('/businesses/{business}-{location}', name: 'get-business' )]
    public function get($business, $location): Response {
        try {
            $businessData = $this->businessInterface->getBusiness($business, $location);

            return new JsonResponse([
                'status' => 'ok', 
                'business' => $businessData
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    #[Route('/businesses/create', name: 'business-create' )]
    public function create(Request $request, UserInterface $user): Response {
        try {
            $data = json_decode($request->getContent(), true);
        
            $this->businessInterface->createBusiness($data, $user->getUserIdentifier());
    
            return new JsonResponse(['status' => 'ok']);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
