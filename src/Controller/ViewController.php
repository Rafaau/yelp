<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Business;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Message;
use App\Form\BusinessFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReviewFormType;
use Doctrine\ORM\EntityManager;
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
    public function search(): Response
    {
        return $this->render('search/index.html.twig');
    }

    #[Route('/user_details', name: 'user_details' )]
    public function user(): Response
    {
        return $this->render('user-details/index.html.twig');
    }

    #[Route('/messaging', name: 'messaging')]
    public function messaging() 
    {
        return $this->render('messaging/index.html.twig');
    }

    #[Route('/messaging/{senderId}-{receiverId}', name: 'conversation')]
    public function conversation() 
    {
        return $this->render('messaging/conversation.html.twig');
    }

    #[Route('/claim', name: 'claim')]
    public function claim(): Response
    {
        return $this->render('business/create.html.twig');
    }

    #[Route('/biz', name: 'business')]
    public function business(): Response
    {
        return $this->render('business/index.html.twig');
    }

    #[Route('/review', name: 'review')]
    public function review(): Response
    {
        return $this->render('review/index.html.twig');
    }

    #[Route('/{location}', name: 'location')]
    public function location(): Response
    {
        return $this->render('index.html.twig');
    }
}
