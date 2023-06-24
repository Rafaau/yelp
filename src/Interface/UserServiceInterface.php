<?php

namespace App\Interface;

use App\Entity\User;

interface UserServiceInterface
{
    public function getUserById(int $id): User;
    public function getUserByUsername(string $username): User;
    public function getCurrentUser(): array;
    public function addFriend(int $friendId, string $username): void;
    public function getUserDetails(int $userId): array;
}