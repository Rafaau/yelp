<?php


namespace App\Tests\unit\entity;

use App\Entity\Review;
use App\Tests\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReviewEntity extends KernelTestCase
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
    public function a_review_can_be_created_in_the_database()
    {
        // Arrange
        $review = $this->fakesFactory->generateReview();
        $business = $this->fakesFactory->generateBusiness();
        $user = $this->fakesFactory->generateUser();
        $this->entityManager->persist($business);
        $this->entityManager->persist($user);
        $review->setBusiness($business);
        $review->setUser($user);

        // Act
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $reviewRepository = $this->entityManager->getRepository(Review::class);
        $reviewFromDatabase = $reviewRepository->findOneBy(['id' => $review->getId()]);

        // Assert
        $this->assertEquals($review->getContent(), $reviewFromDatabase->getContent());
        $this->assertEquals($review->getStars(), $reviewFromDatabase->getStars());
        $this->assertEquals($review->getReactions(), $reviewFromDatabase->getReactions());
    }

    /** @test */
    public function it_throws_an_error_when_stars_is_missing()
    {
        // Arrange
        $review = $this->fakesFactory->generateInvalidReview();
        $business = $this->fakesFactory->generateBusiness();
        $user = $this->fakesFactory->generateUser();
        $this->entityManager->persist($business);
        $this->entityManager->persist($user);
        $review->setBusiness($business);
        $review->setUser($user);

        // Act
        $errors = $this->validator->validate($review);

        // Assert
        $this->assertGreaterThan(0, count($errors), "No validation error for missing stars");
        $this->assertStringContainsString('Stars is required', (string)$errors);
    }
}