<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Business;
use App\Entity\Category;
use App\Entity\User;
use App\Form\BusinessFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReviewFormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
            $businesses = $businessesRepository->findBy(['location' => ucwords($find_loc)]);
        }

        return $this->render('search/index.html.twig', [
            'cflt' => $cflt,
            'location' => $find_loc,
            'findDesc' => $find_desc,
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

    #[Route('/claim', name: 'claim')]
    public function claim(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoryRepository = $this->em->getRepository(Category::class);

        $business = new Business();
        $form = $this->createForm(BusinessFormType::class, $business);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $business->setOwner($this->getUser()->getUserIdentifier());
            $hours = explode(',', $form->get('_hours')->getData());
            dump($hours[5]);
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
            $categories = explode(',', $form->get('_categories')->getData());
            foreach ($categories as $categoryName) {
                dump($categories);
                dump($categoryName);
                $category = $categoryRepository->findOneBy(['name' => $categoryName]);
                if ($category) {     
                    dump($category);
                    $business->addCategory($category);
                }
            }
            $business->setExpensiveness(1);

            $entityManager->persist($business);
            $entityManager->flush();

            return $this->redirectToRoute('business', [
                'business' => $business->getName(),
                'location' => $business->getLocation(),
            ]);
        }

        return $this->render('business/create.html.twig', [
            'view' => 'claim',
            'businessForm' => $form->createView(),
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
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(9)
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

    #[Route('/review/{business}', name: 'review')]
    public function review(string $business, Request $request, EntityManagerInterface $entityManager): Response
    {
        $businessesRepository = $this->em->getRepository(Business::class);
        $business = $businessesRepository->findOneBy(['name' => ucwords($business)]);

        $review = new Review();

        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setBusiness($business);
            $review->setUser($this->getUser());

            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('business', [
                'business' => $business->getName(),
                'location' => $business->getLocation(),
            ]);
        }

        return $this->render('review/index.html.twig', [
            'business' => $business,
            'reviewForm' => $form->createView(),
        ]);
    }
}
