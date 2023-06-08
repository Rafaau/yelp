<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    #[Route('/users/currentUser', name: 'currentUser' )]
    public function getCurrentUser(): Response {
        $user = $this->security->getUser();

        if ($user instanceof \App\Entity\User) {
            return new JsonResponse(['user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'friends' => $user->getFriends(),
                'unreadNotifications' => [],
                'notifications' => [],
            ]]);
        }
    }

    #[Route('/users/add-friend', name: 'add-friend' )]
    public function update(Request $request): Response {
        $data = json_decode($request->getContent(), true);

        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);

        if ($user) {
            $friendToAdd = $this->em->getRepository(User::class)->find($data['id']);
            $user->addFriend($friendToAdd);

            $notification = new Notification();
            $notification->setTitle('New friend request');
            $notification->setMessage($user->getUsername() . ' wants to be your friend!');
            $notification->setUser($friendToAdd);
            $this->em->persist($notification);

            $this->em->flush();
        }

        return new JsonResponse(['status' => 'ok']);
    }
}
