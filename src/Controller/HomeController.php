<?php

namespace App\Controller;

use App\Repository\GamesListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param GamesListRepository $repository
     * @return Response
     */
    public function index(GamesListRepository $repository) : Response
    {
        $games = $repository->last_games();
        return $this->render('pages/home.html.twig', [
            'games' => $games
        ]);
    }
}