<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Tests\unit\entity\FakesFactory;
use Symfony\Component\Validator\Validation;

class UserEntity extends KernelTestCase
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
    public function a_user_can_be_created_in_the_database()
    {
        // Arrange
        $user = $this->fakesFactory->generateUser();

        // Act
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userRepository = $this->entityManager->getRepository(User::class);
        $userFromDatabase = $userRepository->findOneBy(['email' => $user->getEmail()]);

        // Assert
        $this->assertEquals($user->getEmail(), $userFromDatabase->getEmail());
        $this->assertEquals($user->getPassword(), $userFromDatabase->getPassword());
        $this->assertEquals($user->getUsername(), $userFromDatabase->getUsername());
        $this->assertEquals($user->getAddress(), $userFromDatabase->getAddress());
        $this->assertEquals($user->getThingsILove(), $userFromDatabase->getThingsILove());
        $this->assertEquals($user->getMyHometown(), $userFromDatabase->getMyHometown());
        $this->assertEquals($user->getDescription(), $userFromDatabase->getDescription());
        $this->assertEquals($user->getWhenIAmNotWhelping(), $userFromDatabase->getWhenIAmNotWhelping());
        $this->assertEquals($user->getWhyYouShouldRead(), $userFromDatabase->getWhyYouShouldRead());
    }

    /** @test */
    public function it_throws_an_error_when_email_is_missing()
    {
        // Arrange
        $user = $this->fakesFactory->generateInvalidUser();

        // Act
        $errors = $this->validator->validate($user);
        
        // Assert
        $this->assertGreaterThan(0, count($errors), "No validation error for missing email");
        $this->assertStringContainsString('Email is required', (string)$errors);
    }
}