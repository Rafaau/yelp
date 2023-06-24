<?php

namespace App\Controller;

use App\Interface\MessageServiceInterface;
use App\Interface\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    private $messageInterface;
    private $userInterface;

    public function __construct(MessageServiceInterface $messageInterface, UserServiceInterface $userInterface)
    {
        $this->messageInterface = $messageInterface;
        $this->userInterface = $userInterface;
    }

    #[Route('/messages/post', name: 'post-message' )]
    public function post(Request $request): Response {
        $data = json_decode($request->getContent(), true);
        
        $sender = $this->messageInterface->postMessage($data, $this->getUser()->getUserIdentifier());

        return new JsonResponse(['status' => 'ok', 'sender' => $sender->getUsername()]);
    }

    #[Route('/messages', name: 'get-messages' )]
    public function get(Request $request): Response {
        $receiverId = $request->query->get('receiverId');
        $senderId = $request->query->get('senderId');

        $messagesData = $this->messageInterface->getMessages($receiverId, $senderId);
        $receiver = $this->userInterface->getUserById($receiverId);
        
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
        $conversationsData = $this->messageInterface->getConversations($this->getUser());

        return new JsonResponse(['conversations' => $conversationsData]);
    }
}
