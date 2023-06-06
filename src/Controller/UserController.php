<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/users/add-friend', name: 'add-friend' )]
    public function update(Request $request): Response {
        $data = json_decode($request->getContent(), true);

        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUserIdentifier()]);

        if ($user) {
            $friendToAdd = $this->em->getRepository(User::class)->find($data['id']);
            $user->addFriend($friendToAdd);
            $this->em->flush();
        }

        return new JsonResponse(['status' => 'ok']);
    }
}
