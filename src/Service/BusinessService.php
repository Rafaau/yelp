<?php

namespace App\Service;

use App\Entity\Business;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Interface\BusinessServiceInterface;
use App\Repository\BusinessRepository;

class BusinessService implements BusinessServiceInterface
{
    private EntityManagerInterface $entityManager;
    private BusinessRepository $businessRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, BusinessRepository $businessRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
        $this->businessRepository = $businessRepository;
    }

    public function createBusiness(array $data, string $owner): Business
    {
        $business = new Business();

        $business->setName($data['name']);
        $business->setLocation($data['location']);
        $business->setDescription($data['description']);
        $business->setWebsite($data['website']);
        $business->setPhoneNumber($data['phone']);
        $business->setAddress($data['address']);
        $business->setOwner($owner);
        $hours = explode(',', $data['hours']);
        $business->setHours(
            [
                'Mon' => $hours[0],
                'Tue' => $hours[1],
                'Wed' => $hours[2],
                'Thu' => $hours[3],
                'Fri' => $hours[4],
                'Sat' => $hours[5],
                'Sun' => $hours[6],
            ]
        );
        $categories = explode(',', $data['categories']);
        foreach ($categories as $categoryName) {
            $category = $this->categoryRepository->findOneBy(['name' => $categoryName]);
            if ($category) {     
                $business->addCategory($category);
            }
        }
        $business->setExpensiveness(1);

        $this->entityManager->persist($business);
        $this->entityManager->flush();

        return $business;
    }

    public function getBusinesses(string $cflt, string $find_loc): array
    {
        $queryBuilder = $this->businessRepository->createQueryBuilder('b');
        
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

        return $businessesData;
    }

    public function getBusiness(string $business, string $location): array
    {
        $business = $this->businessRepository->findOneBy([
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

        return $businessData;
    }
}
