<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use App\Interface\UserServiceInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function getUserById(int $id): User
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        return $user;
    }

    public function getUserByUsername(string $username): User
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        return $user;
    }

    public function getCurrentUser(): array
    {
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

            return [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'friends' => $friendsData,
                'unreadNotifications' => $unreadNotificationsData,
                'notifications' => $notificationsData,
                'messagesReceived' => $messagesReceived,
                'messagesSent' => $messagesSent,
            ];

        } else {
            return [];
        }
    }

    public function addFriend(int $friendId, string $username): void
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if ($user) {
            $friendToAdd = $this->userRepository->find($friendId);
            $user->addFriend($friendToAdd);

            $notification = new Notification();
            $notification->setTitle('New friend request');
            $notification->setMessage($user->getUsername() . ' wants to be your friend!');
            $notification->setUser($friendToAdd);
            $this->entityManager->persist($notification);

            $this->entityManager->flush();
        }
    }

    public function getUserDetails(int $userId): array
    {
        $user = $this->userRepository->find($userId);

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

        return [
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
        ];
    }
}