<?php
namespace App\Controller;

use App\Repository\GamesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{
    /**
     * @var GamesListRepository
     */
    private $repository;

    public function __construct(GamesListRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/games", name="games.index")
     * @return Response
     */
    public function index(): Response
    {
        $games = $this->repository->findAll();
        return $this->render('games/index.html.twig', [
            'games' => $games
        ]);
    }
}