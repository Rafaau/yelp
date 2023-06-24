<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Interface\NotificationServiceInterface;

class NotificationController extends AbstractController
{
    private $notificationInterface;

    public function __construct(NotificationServiceInterface $notificationInterface)
    {
        $this->notificationInterface = $notificationInterface;
    }

    #[Route('/notifications/mark-as-read', name: 'mark-as-read' )]
    public function update(Request $request): Response {
        $this->notificationInterface->markAsRead($this->getUser()->getUserIdentifier());

        return new JsonResponse(['status' => 'ok']);
    }
}
