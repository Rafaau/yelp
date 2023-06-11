<?php

namespace App\Controller;

use App\Entity\Business;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BusinessController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/businesses', name: 'get-businesses' )]
    public function get(Request $request): Response {
        $cflt = $request->query->get('cflt');
        $find_loc = $request->query->get('find_loc');
        $find_desc = $request->query->get('find_desc');

        $businessesRepository = $this->em->getRepository(Business::class);
        $queryBuilder = $businessesRepository->createQueryBuilder('b');
        
        $businessesRepository = $this->em->getRepository(Business::class);
        $queryBuilder = $businessesRepository->createQueryBuilder('b');
        
        if ($cflt != null) {
            $businesses = $queryBuilder
                ->select('b, AVG(r.stars) as HIDDEN avgStars')
                ->innerJoin('b.categories', 'c')
                ->leftJoin('b.reviews', 'r')
                ->where('b.location = :location')
                ->andWhere('c.name = :category')
                ->groupBy('b.id')
                ->orderBy('avgStars', 'DESC')
                ->setParameters([
                    'location' => ucwords($find_loc),
                    'category' => $cflt,
                ])
                ->getQuery()
                ->getResult();
        } else {
            $businesses = $queryBuilder
                ->select('b, AVG(r.stars) as HIDDEN avgStars')
                ->leftJoin('b.reviews', 'r')
                ->where('b.location = :location')
                ->groupBy('b.id')
                ->orderBy('avgStars', 'DESC')
                ->setParameter('location', ucwords($find_loc))
                ->getQuery()
                ->getResult();
        }

        $businessesData = [];
        foreach ($businesses as $business) {
            $reviewsData = [];
            foreach($business->getReviews() as $review) {
                $reviewsData[] = [
                    'stars' => $review->getStars(),
                    'images' => $review->getImages(),
                    'content' => $review->getContent(),
                ];
            }

            $businessesData[] = [
                'name' => $business->getName(),
                'location' => $business->getLocation(),
                'categories' => $business->getCategories(),
                'reviews' => $reviewsData,
            ];
        }

        return new JsonResponse([
            'status' => 'ok', 
            'businesses' => $businessesData
        ]);
    }

    #[Route('/businesses/create', name: 'business-create' )]
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
