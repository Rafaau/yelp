<?php

namespace App\Service;

use App\Entity\Business;
use App\Entity\Review;
use App\Interface\ReviewServiceInterface;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReviewService implements ReviewServiceInterface
{
    private EntityManagerInterface $entityManager;
    private ReviewRepository $reviewRepository;

    public function __construct(EntityManagerInterface $entityManager, ReviewRepository $reviewRepository)
    {
        $this->entityManager = $entityManager;
        $this->reviewRepository = $reviewRepository;
    }

    public function postReview($data, $user): Review
    {
        $business = $this->entityManager->getRepository(Business::class)->findOneBy(['name' => $data['businessName']]);
        $review = new Review();

        if ($user && $business) {
            $review->setUser($user);
            $review->setBusiness($business);
            $review->setContent($data['content']);
            $review->setStars($data['stars']);

            $this->entityManager->persist($review);
            $this->entityManager->flush();
        }

        return $review;
    }

    public function updateReactions($data): void
    {
        $review = $this->reviewRepository->find($data['id']);

        if ($review) {
            $currentReactions = $review->getReactions();
            $updatedReactions = array_merge($currentReactions, $data['reactions']);
            $review->setReactions($updatedReactions);
            $this->entityManager->flush();
        }
    }

    public function deleteReview(int $id): void
    {
        $review = $this->reviewRepository->find($id);

        if ($review) {
            $this->entityManager->remove($review);
            $this->entityManager->flush();
        }
    }

    public function getReviews(string $location, int $page, int $limit): array
    {
        $queryBuilder = $this->reviewRepository->createQueryBuilder('r');

        $reviews = $queryBuilder
            ->select('r, b, u')
            ->innerJoin('r.business', 'b')
            ->innerJoin('r.user', 'u')
            ->where('b.location = :location')
            ->setParameter('location', ucwords($location))
            ->orderBy('r.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    
        $reviewsData = [];
        foreach ($reviews as $review) {
            $reviewsData[] = [
                'id' => $review->getId(),
                'content' => $review->getContent(),
                'stars' => $review->getStars(),
                'reactions' => $review->getReactions(),
                'images' => $review->getImages(),
                'user' => array(
                    'id' => $review->getUser()->getId(),
                    'username' => $review->getUser()->getUsername(),
                    'userImage' => $review->getUser()->getUserImage(),
                ),
                'business' => array(
                    'name' => $review->getBusiness()->getName(),
                ),
            ];
        }

        return $reviewsData;
    }
}