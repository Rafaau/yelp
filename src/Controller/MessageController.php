<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/messages/post', name: 'post-message' )]
    public function post(Request $request): Response {
        $data = json_decode($request->getContent(), true);
        $sender = $this->em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);
        $receiver = $this->em->getRepository(User::class)->findOneBy(['id' => $data['receiverId']]);
        $message = new Message();

        if ($sender && $receiver) {
            $message->setSender($sender);
            $message->setReceiver($receiver);
            $message->setContent( $data['content']);
            $this->em->persist($message);

            $this->em->flush();
        }

        return new JsonResponse(['status' => 'ok', 'sender' => $sender->getUsername()]);
    }

    #[Route('/messages', name: 'get-messages' )]
    public function get(Request $request): Response {
        $receiverId = $request->query->get('receiverId');
        $senderId = $request->query->get('senderId');

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
        
        return new JsonResponse([
            'messages' => $messagesData,
            'receiver' => [
                'id' => $receiver->getId(),
                'username' => $receiver->getUsername(),
                'userImage' => $receiver->getUserImage() ?? 'build/images/avatar_default.19e0a8ff.jpg'
            ],
        ]);
    }

    #[Route('/messages/conversations', name: 'get-conversations' )]
    public function getConversations() {
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

        return new JsonResponse(['conversations' => $conversationsData]);
    }
}
