<?php

namespace App\Controller\API;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em = null) {
        $this->em = $em;
    }
    /**
     * @Route("/api/v1/users", methods={"GET"}, name="users_list_api")
     */
    public function index(SerializerInterface $serializer): Response
    {
        $users = ($this->em->getRepository(User::class))->findAll();
        $jsonData = $serializer->normalize(
            $users,
            null,
            ['groups' => 'user:show']
        );
        return $this->json(['users' => $jsonData]);
    }
}
