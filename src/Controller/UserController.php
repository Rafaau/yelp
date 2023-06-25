<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Interface\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    private UserServiceInterface $userInterface;

    public function __construct(UserServiceInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    #[Route('/users/currentUser', name: 'current-user' )]
    public function getCurrentUser(): Response {
        try {
            $user = $this->userInterface->getCurrentUser();

            return new JsonResponse(['user' => $user ? $user : null]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
 
    #[Route('/users/add-friend', name: 'add-friend' )]
    public function update(Request $request, UserInterface $user): Response {
        try {
            $data = json_decode($request->getContent(), true);

            $this->userInterface->addFriend($data['id'], $user->getUserIdentifier());
    
            return new JsonResponse(['status' => 'ok']);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    #[Route('/users/{userId}', name: 'get-user' )]
    public function getUserDetails($userId): Response {
        try {
            $user = $this->userInterface->getUserDetails($userId);

            return new JsonResponse(['user' => $user]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
