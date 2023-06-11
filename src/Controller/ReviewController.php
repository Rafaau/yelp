<?php

namespace App\Controller;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/reviews/update_reaction', name: 'review-update' )]
    public function update(Request $request): Response {
        $data = json_decode($request->getContent(), true);

        $review = $this->em->getRepository(Review::class)->find($data['id']);

        if ($review) {
            $currentReactions = $review->getReactions();
            $updatedReactions = array_merge($currentReactions, $data['reactions']);
            $review->setReactions($updatedReactions);
            $this->em->flush();
        }

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/reviews/delete', name: 'review-delete' )]
    public function delete(Request $request): Response {
        $data = json_decode($request->getContent(), true);

        $review = $this->em->getRepository(Review::class)->find($data['id']);

        if ($review) {
            $this->em->remove($review);
            $this->em->flush();
        }

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/reviews/{location}/{page}', name: 'review-load' )]
    public function getReviews($location, $page = 1, $limit = 9): Response {
        $reviewsRepository = $this->em->getRepository(Review::class);
        $queryBuilder = $reviewsRepository->createQueryBuilder('r');

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
        
        return new JsonResponse(['reviews' => $reviewsData]);            
    }
}
