<?php
namespace App\Controller\admin;

use App\Entity\GamesList;
use App\Form\GameListType;
use App\Repository\GamesListRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminGamesController extends AbstractController
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
     * @Route("/admin", name="admin.index")
     * @return Response
     */
    public function index(): Response
    {
        $games = $this->repository->findAll();
        return $this->render('admin/index.html.twig', [
            'games' => $games
        ]);
    }

    /**
     * @Route("/admin/{id}", name="admin.game.edit", methods="POST|GET")
     * @param GamesList $games
     * @param Request $request
     * @return Response
     */
    public function editGame(GamesList $games, Request $request): Response
    {
        $form = $this->createForm(GameListType::class, $games);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->manager->persist($games);
            $this->manager->flush();
            $this->addFlash('success', 'Modification prise en compte');
            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/games/editgame.html.twig', [
            'games' => $games,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/{id}", name="admin.game.delete", methods="DELETE")
     * @param GamesList $games
     * @param Request $request
     * @return Response
     */

    public function deleteGame(GamesList $games, Request $request): Response
    {
        if($this->isCsrfTokenValid('del'.$games->getId(), $request->get('_token')))
        {
            $this->manager->remove($games);
            $this->manager->flush();
            $this->addFlash('success', 'Suppression réalisée avec succes');
        }
        return $this->redirectToRoute('admin.index');
    }
}