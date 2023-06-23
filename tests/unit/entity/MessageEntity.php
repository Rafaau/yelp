<?php


namespace App\Tests\unit\entity;

use App\Entity\Message;
use App\Tests\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessageEntity extends KernelTestCase
{
	/** @var EntityManagerInterface */
    private $entityManager;
    private $fakesFactory;
    private $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->fakesFactory = new FakesFactory();
        $this->validator = static::getContainer()->get('validator');

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /** @test */
    public function a_message_can_be_created_in_the_database()
    {
        // Arrange
        $message = $this->fakesFactory->generateMessage();
        $sender = $this->fakesFactory->generateUser();
        $this->entityManager->persist($sender);
        $message->setSender($sender);
        $receiver = $this->fakesFactory->generateUser();
        $this->entityManager->persist($receiver);
        $message->setReceiver($receiver);

        // Act
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $messageRepository = $this->entityManager->getRepository(Message::class);
        $messageFromDatabase = $messageRepository->findOneBy(['id' => $message->getId()]);

        // Assert
        $this->assertEquals($message->getContent(), $messageFromDatabase->getContent());
        $this->assertEquals($message->getSender(), $messageFromDatabase->getSender());
        $this->assertEquals($message->getReceiver(), $messageFromDatabase->getReceiver());
        $this->assertEquals($message->getPostDate(), $messageFromDatabase->getPostDate());
    }

    /** @test */
    public function it_throws_an_error_when_content_is_missing()
    {
        // Arrange
        $message = $this->fakesFactory->generateInvalidMessage();
        $sender = $this->fakesFactory->generateUser();
        $this->entityManager->persist($sender);
        $message->setSender($sender);
        $receiver = $this->fakesFactory->generateUser();
        $this->entityManager->persist($receiver);
        $message->setReceiver($receiver);

        // Act
        $errors = $this->validator->validate($message);

        // Assert
        $this->assertGreaterThan(0, count($errors), "No validation error for missing content");
        $this->assertStringContainsString('Content is required', (string)$errors);
    }
}