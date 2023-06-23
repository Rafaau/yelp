<?php

namespace App\Controller;

use App\Entity\Business;
use App\Entity\Category;
use App\Interface\BusinessServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BusinessController extends AbstractController
{
    private $em;
    private $businessInterface;

    public function __construct(EntityManagerInterface $em, BusinessServiceInterface $businessInterface)
    {
        $this->em = $em;
        $this->businessInterface = $businessInterface;
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
            $totalStars = 0;
            $totalReviews = 0;
            foreach($business->getReviews() as $review) {
                $totalStars += $review->getStars();
                $totalReviews++;
                $reviewsData[] = [
                    'stars' => $review->getStars(),
                    'images' => $review->getImages(),
                    'content' => $review->getContent(),
                ];
            }

            $avgStars = $totalReviews > 0 ? $totalStars / $totalReviews : 0;

            $businessesData[] = [
                'name' => $business->getName(),
                'location' => $business->getLocation(),
                'categories' => $business->getCategories(),
                'reviews' => $reviewsData,
                'avgStars' => $avgStars,
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

        $totalStars = 0;
        $totalReviews = 0;

        $reviewsData = [];
        foreach($business->getReviews() as $review) {
            $totalStars += $review->getStars();
            $totalReviews++;
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
                'id' => $review->getId(),
                'stars' => $review->getStars(),
                'images' => $review->getImages(),
                'content' => $review->getContent(),
                'user' => array(
                    'id' => $review->getUser()->getId(),
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

        $avgStars = $totalReviews > 0 ? $totalStars / $totalReviews : 0;

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
            'avgStars' => $avgStars,
        ];

        return new JsonResponse([
            'status' => 'ok', 
            'business' => $businessData
        ]);
    }

    #[Route('/businesses/create', name: 'business-create' )]
    public function update(Request $request): Response {
        $data = json_decode($request->getContent(), true);
        
        $business = $this->businessInterface->createBusiness($data, $this->getUser()->getUserIdentifier());

        return new JsonResponse(['status' => $business ? 'ok' : 'error']);
    }
}
