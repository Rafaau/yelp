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

class UserController extends AbstractController
{
    private UserServiceInterface $userInterface;

    public function __construct(UserServiceInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    #[Route('/users/currentUser', name: 'current-user' )]
    public function getCurrentUser(): Response {
        $user = $this->userInterface->getCurrentUser();

        return new JsonResponse(['user' => $user ? $user : null]);
    }
 
    #[Route('/users/add-friend', name: 'add-friend' )]
    public function update(Request $request): Response {
        $data = json_decode($request->getContent(), true);

        $this->userInterface->addFriend($data['id'], $this->getUser()->getUserIdentifier());

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/users/{userId}', name: 'get-user' )]
    public function getUserDetails($userId): Response {
        $user = $this->userInterface->getUserDetails($userId);

        return new JsonResponse(['user' => $user]);
    }
}
