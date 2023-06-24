<?php

namespace App\Service;

use App\Entity\Message;
use App\Entity\User;
use App\Interface\MessageServiceInterface;
use App\Interface\UserServiceInterface;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;

class MessageService implements MessageServiceInterface
{
    private EntityManagerInterface $entityManager;
    private UserServiceInterface $userInterface;
    private MessageRepository $messageRepository;

    public function __construct(EntityManagerInterface $entityManager, UserServiceInterface $userInterface, MessageRepository $messageRepository)
    {
        $this->entityManager = $entityManager;
        $this->userInterface = $userInterface;
        $this->messageRepository = $messageRepository;
    }

    public function postMessage(array $data, string $userIdentifier): User
    {
        $sender = $this->userInterface->getUserByUsername($userIdentifier);
        $receiver = $this->userInterface->getUserById($data['receiverId']);
        $message = new Message();

        if ($sender && $receiver) {
            $message->setSender($sender);
            $message->setReceiver($receiver);
            $message->setContent( $data['content']);
            $this->entityManager->persist($message);

            $this->entityManager->flush();
        }

        return $sender;
    }

    public function getMessages($receiverId, $senderId): array
    {
        $receiver = $this->userInterface->getUserById($receiverId);
        $sender = $this->userInterface->getUserById($senderId);

        $messagesFromSender = $this->messageRepository->findBy(
            ['sender' => $sender, 'receiver' => $receiver],
            ['id' => 'DESC'],
            10
        );
        
        $messagesFromReceiver = $this->messageRepository->findBy(
            ['sender' => $receiver, 'receiver' => $sender],
            ['id' => 'DESC'],
            10
        );
        
        $messages = array_merge($messagesFromSender, $messagesFromReceiver);
        usort($messages, function ($a, $b) {
            return $a->getId() - $b->getId();
        });

        $messagesData = [];
        foreach ($messages as $message) {
            $messagesData[] = [
                'id' => $message->getId(),
                'sender' => [
                    'id' => $message->getSender()->getId(),
                    'username' => $message->getSender()->getUsername(),
                    'userImage' => $message->getSender()->getUserImage() ?? 'build/images/avatar_default.19e0a8ff.jpg'
                ],
                'receiver' => [
                    'id' => $message->getReceiver()->getId(),
                    'username' => $message->getReceiver()->getUsername(),
                    'userImage' => $message->getReceiver()->getUserImage() ?? 'build/images/avatar_default.19e0a8ff.jpg'
                ],
                'content' => $message->getContent(),
                'postDate' => $message->getPostDate(),
            ];
        }

        return $messagesData;
    }

    public function getConversations($user): array
    {
        $conversations = $this->messageRepository->createQueryBuilder('m')
            ->select('m, u')
            ->innerJoin('m.sender', 'u')
            ->where('m.receiver = :user')
            ->orWhere('m.sender = :user')
            ->groupBy('u.id')
            ->orderBy('m.id', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        $conversationsData = [];
        foreach ($conversations as $conversation) {
            $conversationsData[] = [
                'id' => $conversation->getId(),
                'sender' => [
                    'id' => $conversation->getSender()->getId(),
                    'username' => $conversation->getSender()->getUsername(),
                    'userImage' => $conversation->getSender()->getUserImage() ?? 'build/images/avatar_default.19e0a8ff.jpg'
                ],
                'receiver' => [
                    'id' => $conversation->getReceiver()->getId(),
                    'username' => $conversation->getReceiver()->getUsername(),
                    'userImage' => $conversation->getReceiver()->getUserImage() ?? 'build/images/avatar_default.19e0a8ff.jpg'
                ],
                'content' => $conversation->getContent(),
                'postDate' => $conversation->getPostDate(),
            ];
        }

        return $conversationsData;
    }
}