<?php

namespace App\Interface;

interface NotificationServiceInterface
{
    public function markAsRead(string $username): void;
}