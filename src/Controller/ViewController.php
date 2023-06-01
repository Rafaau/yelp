<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Business;
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

    #[Route('/{location}', name: 'location')]
    public function location(string $location): Response
    {
        $reviewsRepository = $this->em->getRepository(Review::class);
        $reviews = $reviewsRepository->findAll();

        return $this->render('index.html.twig', [
            'location' => $location,
            'reviews' => $reviews,
        ]);
    }
}
