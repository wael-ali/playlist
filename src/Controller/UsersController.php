<?php

namespace App\Controller;

use App\Entity\Mp3;
use App\Entity\User;
use App\Entity\Playlist;
use App\Service\PaginatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    /**
     * @Route("/users", name="users_list")
     */
    public function index(Request $request): Response
    {
        $limit = 50;
        // $currentPage = $request->query->get('page') ?? 1;
        // $currentPage = $currentPage > 0 ? $currentPage : 1;
        $count = count(($this->em->getRepository(User::class))->findAll());
        // $totalpages = ($count / $limit) + ($count % $limit > 0 ? 1 : 0); 
        // $currentPage = $currentPage > $totalpages ? 1 : $currentPage;
        // $previousPage = $currentPage - 1;
        // $previousPage = $previousPage > $totalpages ? 0 : $previousPage;
        $paginator = new PaginatorService($request, $count, $limit);
        // dd($paginator);
        $offset = $paginator->getCurrentPage() === 1 ? 1 : $paginator->getCurrentPage() * $limit;
        // $nextPage = $count - (($currentPage + 1) * $limit) > 0 ? ($currentPage + 1) : 0;
        // $nextPage = $nextPage > $totalpages ? 0 : $nextPage;
        // dd($currentPage, $previousPage, $nextPage);
        $users = ($this->em->getRepository(User::class))->findPaginated($offset, $limit);
        // $mp3s = ($this->em->getRepository(Mp3::class))->findAll();
        // $playlists = ($this->em->getRepository(Playlist::class))->findAll();

        // dd($playlists[0]);
        return $this->render('users/index.html.twig', [
            'count' => $count,
            'totalPages' => $paginator->getTotalPages(),
            'users' => $users,
            'currentPage' => $paginator->getCurrentPage(),
            'nextPage' => $paginator->getNextPage(),
            'previousPage' => $paginator->getPreviousPage(),
            'mp3s' => [],
            'playlists' => [],
        ]);
    }
}
