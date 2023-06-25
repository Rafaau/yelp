<?php

namespace App\Controller;

use App\Interface\ReviewServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewController extends AbstractController
{
    private $reviewInterface;

    public function __construct(ReviewServiceInterface $reviewInterface)
    {
        $this->reviewInterface = $reviewInterface;
    }

    #[Route('/reviews/post', name: 'review-post' )]
    public function post(Request $request, UserInterface $user): Response {
        try {
            $data = json_decode($request->getContent(), true);
        
            $review = $this->reviewInterface->postReview($data, $user);
    
            return new JsonResponse(['status' => $review ? 'ok' : 'error']);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    #[Route('/reviews/update_reaction', name: 'review-update' )]
    public function update(Request $request): Response {
        try {
            $data = json_decode($request->getContent(), true);

            $this->reviewInterface->updateReactions($data);
    
            return new JsonResponse(['status' => 'ok']);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    #[Route('/reviews/delete', name: 'review-delete' )]
    public function delete(Request $request): Response {
        try {
            $data = json_decode($request->getContent(), true);

            $this->reviewInterface->deleteReview($data['id']);
    
            return new JsonResponse(['status' => 'ok']);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    #[Route('/reviews/{location}/{page}', name: 'review-load' )]
    public function getReviews($location, $page = 1, $limit = 9): Response {
        try {
            $reviewsData = $this->reviewInterface->getReviews($location, $page, $limit);
        
            return new JsonResponse(['reviews' => $reviewsData]);    
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
