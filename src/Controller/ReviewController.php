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
}
