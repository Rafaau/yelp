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
}
