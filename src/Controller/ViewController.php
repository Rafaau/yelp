<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Business;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'homepage' )]
    public function index(): Response
    {
        return $this->redirect('/london');
    }

    #[Route('/search', name: 'search' )]
    public function search(Request $request): Response
    {
        $cflt = $request->query->get('cflt');
        $find_loc = $request->query->get('find_loc');
        $businessesRepository = $this->em->getRepository(Business::class);
        $queryBuilder = $businessesRepository->createQueryBuilder('b');
        
        $businessesRepository = $this->em->getRepository(Business::class);
        $queryBuilder = $businessesRepository->createQueryBuilder('b');
        
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
        

        return $this->render('search/index.html.twig', [
            'cflt' => $cflt,
            'location' => $find_loc,
            'businesses' => $businesses,
        ]);
    }

    #[Route('/user_details', name: 'user_details' )]
    public function user(Request $request): Response
    {
        $userid = $request->query->get('userid');
        $location = $request->query->get('location');
        $userRepository = $this->em->getRepository(User::class);
        
        $user = $userRepository->findOneBy(['id' => $userid]);

        return $this->render('user-details/index.html.twig', [
            'location' => $location,
            'user' => $user,
        ]);
    }

    #[Route('/{location}', name: 'location')]
    public function location(string $location): Response
    {
        $reviewsRepository = $this->em->getRepository(Review::class);
        $queryBuilder = $reviewsRepository->createQueryBuilder('r');

        $reviews = $queryBuilder
            ->select('r, b')
            ->innerJoin('r.business', 'b')
            ->where('b.location = :location')
            ->setParameter('location', ucwords($location))
            ->getQuery()
            ->getResult();

        return $this->render('index.html.twig', [
            'location' => $location,
            'reviews' => $reviews,
        ]);
    }

    #[Route('/biz/{business}-{location}', name: 'business')]
    public function business(string $business, string $location): Response
    {
        $reviewsRepository = $this->em->getRepository(Review::class);
        $businessesRepository = $this->em->getRepository(Business::class);

        $queryBuilder = $reviewsRepository->createQueryBuilder('r');

        $reviews = $queryBuilder
            ->select('r, b')
            ->innerJoin('r.business', 'b')
            ->where('b.name = :business')
            ->setParameter('business', ucwords($business))
            ->getQuery()
            ->getResult();

        $business = $businessesRepository->findOneBy(['name' => ucwords($business)]);

        $imageCount = 0;
        foreach ($business->getReviews() as $review) {
            $imageCount += count($review->getImages());
        }

        return $this->render('business/index.html.twig', [
            'reviews' => $reviews,
            'business' => $business,
            'location' => $location,
            'imageCount' => $imageCount,
        ]);
    }
}
