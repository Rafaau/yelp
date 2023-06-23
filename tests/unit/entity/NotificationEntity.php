<?php


namespace App\Tests\unit\entity;

use App\Entity\Notification;
use App\Tests\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NotificationEntity extends KernelTestCase
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
    public function a_notification_can_be_created_in_the_database()
    {
        // Arrange
        $notification = $this->fakesFactory->generateNotification();
        $user = $this->fakesFactory->generateUser();
        $this->entityManager->persist($user);
        $notification->setUser($user);

        // Act
        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        $notificationRepository = $this->entityManager->getRepository(Notification::class);
        $notificationFromDatabase = $notificationRepository->findOneBy(['id' => $notification->getId()]);

        // Assert
        $this->assertEquals($notification->getTitle(), $notificationFromDatabase->getTitle());
        $this->assertEquals($notification->getMessage(), $notificationFromDatabase->getMessage());
        $this->assertEquals($notification->isIsRead(), $notificationFromDatabase->isIsRead());
        $this->assertEquals($notification->getUser(), $notificationFromDatabase->getUser());
    }

    /** @test */
    public function it_throws_an_error_when_title_is_missing()
    {
        // Arrange
        $notification = $this->fakesFactory->generateInvalidNotification();

        // Act
        $errors = $this->validator->validate($notification);

        // Assert
        $this->assertGreaterThan(0, count($errors), "No validation error for missing title");
        $this->assertStringContainsString('Title is required', (string)$errors);
    }
}