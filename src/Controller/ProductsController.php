<?php

namespace App\Controller;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: '' )]
    public function index(): Response
    {
        $reviewsRepository = $this->em->getRepository(Review::class);
        $reviews = $reviewsRepository->findAll();

        return $this->render('index.html.twig', [
            'reviews' => $reviews,
        ]);
    }
}
