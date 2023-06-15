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

    #[Route('/messaging', name: 'messaging')]
    public function messaging() 
    {
        $messageRepository = $this->em->getRepository(Message::class);

        $conversations = $messageRepository->createQueryBuilder('m')
            ->select('m, u')
            ->innerJoin('m.sender', 'u')
            ->where('m.receiver = :user')
            ->orWhere('m.sender = :user')
            ->groupBy('u.id')
            ->orderBy('m.id', 'DESC')
            ->setParameter('user', $this->getUser())
            ->getQuery()
            ->getResult();

        return $this->render('messaging/index.html.twig', [
            'view' => 'messaging',
            'conversations' => $conversations,
        ]);
    }

    #[Route('/messaging/{senderId}-{receiverId}', name: 'conversation')]
    public function conversation($senderId, $receiverId) 
    {
        $receiver = $this->em->getRepository(User::class)->findOneBy(['id' => $receiverId]);
        $sender = $this->em->getRepository(User::class)->findOneBy(['id' => $senderId]);

        $messageRepository = $this->em->getRepository(Message::class);

        $messagesFromSender = $messageRepository->findBy(
            ['sender' => $sender, 'receiver' => $receiver],
            ['id' => 'DESC'],
            10
        );
        
        $messagesFromReceiver = $messageRepository->findBy(
            ['sender' => $receiver, 'receiver' => $sender],
            ['id' => 'DESC'],
            10
        );
        
        $messages = array_merge($messagesFromSender, $messagesFromReceiver);
        usort($messages, function ($a, $b) {
            return $a->getId() - $b->getId();
        });
        
        return $this->render('messaging/conversation.html.twig', [
            'view' => 'messaging',
            'messages' => $messages,
            'receiver' => $receiver,
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

    #[Route('/biz', name: 'business')]
    public function business(Request $request): Response
    {
        return $this->render('business/index.html.twig');
    }

    #[Route('/review', name: 'review')]
    public function review(Request $request, EntityManagerInterface $entityManager): Response
    {
        $business = $request->query->get('business');
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
}
