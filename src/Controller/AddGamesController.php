<?php
namespace App\Controller;

use App\Entity\GamesList;
use App\Form\GameListType;
use App\Repository\GamesListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddGamesController extends AbstractController
{

    /**
     * @var GamesListRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @param GamesListRepository $repository
     * @param EntityManagerInterface $manager
     */
    public function __construct(GamesListRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @Route("/games/addgame", name="games.addGame")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $game = new GamesList();
        $form = $this->createForm(GameListType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->manager->persist($game);
            $this->manager->flush();
            $this->addFlash('success', 'Jeu ajouté avec succès');
            return $this->redirectToRoute('games.index');
        }

        return $this->render('games/addgame.html.twig', [
           'games' => $game,
            'form' => $form->createView()
        ]);

    }

}