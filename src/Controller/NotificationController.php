<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/notifications/mark-as-read', name: 'mark-as-read' )]
    public function update(Request $request): Response {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);

        if ($user) {
            $unreadNotifications = $user->getUnreadNotifications();
            foreach ($unreadNotifications as $notification) {
                $notification->setIsRead(true);
            }

            $this->em->flush();
        }

        return new JsonResponse(['status' => 'ok']);
    }
}
