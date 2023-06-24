<?php

namespace App\Service;

use App\Interface\NotificationServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService implements NotificationServiceInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function markAsRead(string $username): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if ($user) {
            $unreadNotifications = $user->getUnreadNotifications();
            foreach ($unreadNotifications as $notification) {
                $notification->setIsRead(true);
            }

            $this->entityManager->flush();
        }
    }
}