<?php

namespace App\Interface;

use App\Entity\User;

interface MessageServiceInterface
{
    public function postMessage(array $data, string $userIdentifier): User;
    public function getMessages($receiverId, $senderId): array;
    public function getConversations($user): array;
}