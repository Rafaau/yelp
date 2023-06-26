<?php

namespace App\Tests\Unit\Service;

use App\Entity\Message;
use App\Entity\User;
use App\Interface\UserServiceInterface;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use App\Service\MessageService as ServiceMessageService;

class MessageService extends TestCase
{
    private $entityManager;
    private $messageRepository;
    private $userInterface;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->messageRepository = $this->createMock(MessageRepository::class);
        $this->userInterface = $this->createMock(UserServiceInterface::class);
    }

    public function tearDown(): void
    {
        $this->entityManager = null;
        $this->messageRepository = null;
        $this->userInterface = null;
    }

    /** @test */
    public function should_return_sender_when_data_is_valid()
    {
        $sender = $this->createMock(User::class);
        $receiver = $this->createMock(User::class);

        $this->userInterface->method('getUserByUsername')->willReturn($sender);
        $this->userInterface->method('getUserById')->willReturn($receiver);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Message::class));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $data = ['receiverId' => 1, 'content' => 'testContent'];
        $userIdentifier = 'testIdentifier';

        $messageService = new ServiceMessageService($this->entityManager, $this->userInterface, $this->messageRepository);

        $result = $messageService->postMessage($data, $userIdentifier);

        $this->assertSame($sender, $result);
    }
}