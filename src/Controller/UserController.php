<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    #[Route('/users/currentUser', name: 'current-user' )]
    public function getCurrentUser(): Response {
        $user = $this->security->getUser();

        if ($user instanceof \App\Entity\User) {

            $friendsData = [];
            foreach ($user->getFriends() as $friend) {
                $friendsData[] = [
                    'id' => $friend->getId(),
                    'username' => $friend->getUsername(),
                ];
            }

            $messagesReceived = [];
            foreach ($user->getMessagesReceived() as $message) {
                $messagesReceived[] = [
                    'id' => $message->getId(),
                    'sender' => $message->getSender()->getUsername(),
                    'content' => $message->getContent(),
                    'postDate' => $message->getPostDate(),
                ];
            }

            $messagesSent = [];
            foreach ($user->getMessagesSent() as $message) {
                $messagesSent[] = [
                    'id' => $message->getId(),
                    'receiver' => $message->getReceiver()->getUsername(),
                    'content' => $message->getContent(),
                    'postDate' => $message->getPostDate(),
                ];
            }

            $notificationsData = [];
            foreach ($user->getNotifications() as $notification) {
                $notificationsData[] = [
                    'id' => $notification->getId(),
                    'title' => $notification->getTitle(),
                    'message' => $notification->getMessage(),
                    'isRead' => $notification->isIsRead()
                ];
            }

            $unreadNotificationsData = [];
            foreach ($user->getUnreadNotifications() as $notification) {
                $unreadNotificationsData[] = [
                    'id' => $notification->getId(),
                    'title' => $notification->getTitle(),
                    'message' => $notification->getMessage(),
                ];
            }

            return new JsonResponse(['user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'friends' => $friendsData,
                'unreadNotifications' => $unreadNotificationsData,
                'notifications' => $notificationsData,
                'messagesReceived' => $messagesReceived,
                'messagesSent' => $messagesSent,
            ]]);
        }
    }
 
    #[Route('/users/add-friend', name: 'add-friend' )]
    public function update(Request $request): Response {
        $data = json_decode($request->getContent(), true);

        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);

        if ($user) {
            $friendToAdd = $this->em->getRepository(User::class)->find($data['id']);
            $user->addFriend($friendToAdd);

            $notification = new Notification();
            $notification->setTitle('New friend request');
            $notification->setMessage($user->getUsername() . ' wants to be your friend!');
            $notification->setUser($friendToAdd);
            $this->em->persist($notification);

            $this->em->flush();
        }

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/users/{userId}', name: 'get-user' )]
    public function getUserDetails($userId): Response {
        $user = $this->em->getRepository(User::class)->find($userId);

        $reviewsData = [];
        foreach ($user->getReviews() as $review) {
            $businessReviews = [];
            foreach ($review->getBusiness()->getReviews() as $businessReview) {
                $businessReviews[] = [
                    'images' => $businessReview->getImages(),
                ];
            }

            $businessCategories = [];
            foreach ($review->getBusiness()->getCategories() as $businessCategory) {
                $businessCategories[] = [
                    'name' => $businessCategory->getName(),
                ];
            }

            $reviewsData[] = [
                'id' => $review->getId(),
                'business' => array(
                    'id' => $review->getBusiness()->getId(),
                    'name' => $review->getBusiness()->getName(),
                    'categories' => $businessCategories,
                    'reviews' => $businessReviews,
                    'location' => $review->getBusiness()->getLocation(),
                    'address' => $review->getBusiness()->getAddress(),
                ),
                'user' => $review->getUser(),
                'stars' => $review->getStars(),
                'content' => $review->getContent(),
                'reactions' => $review->getReactions(),
                'postDate' => $review->getPostDate(),
            ];
        }

        $friendsData = [];
        foreach ($user->getFriends() as $friend) {
            $friendsData[] = [
                'id' => $friend->getId(),
                'username' => $friend->getUsername(),
            ];
        }

        return new JsonResponse(['user' => [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'friends' => $friendsData,
            'address' => $user->getAddress(),
            'userImage' => $user->getUserImage(),
            'memberSince' => $user->getMemberSince(),
            'unreadNotifications' => [],
            'notifications' => [],
            'reviews' => $reviewsData,
        ]]);
    }
}
