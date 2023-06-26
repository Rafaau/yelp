<?php

namespace App\Tests\Unit\Service;

use App\Entity\Business;
use App\Entity\Review;
use App\Entity\User;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use App\Service\ReviewService as ServiceReviewService;
use Doctrine\ORM\EntityRepository;

class ReviewService extends TestCase
{
    private $entityManager;
    private $reviewRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->reviewRepository = $this->createMock(ReviewRepository::class);
    }

    public function tearDown(): void
    {
        $this->entityManager = null;
        $this->reviewRepository = null;
    }

    /** @test */
    public function should_return_review_when_data_is_valid()
    {
        $user = new User();
        $business = new Business();
        $data = [
            'businessName' => 'testBusiness',
            'content' => 'testContent',
            'stars' => 5,
        ];

        $repository = $this->createMock(EntityRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => $data['businessName']])
            ->willReturn($business);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Business::class)
            ->willReturn($repository);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Review::class));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $reviewService = new ServiceReviewService($this->entityManager, $this->reviewRepository);

        $review = $reviewService->postReview($data, $user);

        $this->assertInstanceOf(Review::class, $review);
        $this->assertEquals($data['content'], $review->getContent());
        $this->assertEquals($data['stars'], $review->getStars());
        $this->assertEquals($user, $review->getUser());
        $this->assertEquals($business, $review->getBusiness());
    }
}