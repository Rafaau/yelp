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
    public function getAll(Request $request): Response {
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

    #[Route('/businesses/{business}-{location}', name: 'get-business' )]
    public function get($business, $location): Response {
        $businessesRepository = $this->em->getRepository(Business::class);

        $business = $businessesRepository->findOneBy([
            'name' => ucwords($business), 
            'location' => ucwords($location)
        ]);

        $reviewsData = [];
        foreach($business->getReviews() as $review) {
            $userFriends = [];
            foreach($review->getUser()->getFriends() as $friend) {
                $userFriends[] = [
                    'id' => $friend->getId(),
                ];
            }

            $userReviews = [];
            foreach($review->getUser()->getReviews() as $userReview) {
                $userReviews[] = [
                    'id' => $userReview->getId(),
                ];
            }

            $reviewsData[] = [
                'stars' => $review->getStars(),
                'images' => $review->getImages(),
                'content' => $review->getContent(),
                'user' => array(
                    'username' => $review->getUser()->getUsername(),
                    'userImage' => $review->getUser()->getUserImage(),
                    'friends' => $userFriends,
                    'reviews' => $userReviews,
                    'address' => $review->getUser()->getAddress(),
                ),
                'postDate' => $review->getPostDate(),
                'reactions' => $review->getReactions(),
            ];
        }

        $categories = [];
        foreach($business->getCategories() as $category) {
            $categories[] = [
                'name' => $category->getName(),
            ];
        }

        $businessData = [
            'name' => $business->getName(),
            'location' => $business->getLocation(),
            'categories' => $business->getCategories(),
            'reviews' => $reviewsData,
            'owner' => $business->getOwner(),
            'expensiveness' => $business->getExpensiveness(),
            'hours' => $business->getHours(),
            'categories' => $categories,
            'description' => $business->getDescription(),
            'website' => $business->getWebsite(),
            'phoneNumber' => $business->getPhoneNumber(),
            'address' => $business->getAddress(),
        ];

        return new JsonResponse([
            'status' => 'ok', 
            'business' => $businessData
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
