<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\PlayersGame;
use App\Entity\Rating;
use App\Entity\User;
use App\Form\AddGameType;
use App\Form\AddPlayerType;
use App\Form\CompetitionType;
use App\Form\EndGameType;
use App\Form\GameType;
use App\Service\CompetitionService;
use App\Service\GameService;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CompetitionController extends AbstractController
{
    private $competitionService;
    private $playerService;
    private $gameService;

    public function __construct(PlayerService $playerService, CompetitionService $competitionService, GameService $gameService)
    {
        $this->competitionService = $competitionService;
        $this->playerService = $playerService;
        $this->gameService = $gameService;
    }

    /**
     * @Route("/competition", name="competition")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $competition = $em->getRepository(Competition::class)->findBy(['public' => true], ['id' => 'DESC']);
        return $this->render('competition/index.html.twig', [
            'competitions' => $competition,
        ]);
    }

    /**
     * @Route("/competition/create", name="create_competition")
     */
    public function createCompetition(Request $request): Response
    {
        $lider = $this->getUser();
        $competition = new Competition();
        $competitionForm= $this->createForm(CompetitionType::class, $competition);
        $competitionForm->handleRequest($request);

        if ($competitionForm->isSubmitted() && $competitionForm->isValid()) {
            $this->competitionService->createCompetition($competition, $lider);

            return $this->redirectToRoute('admin_competition', ['competition' => $competition->getId()]);
        }

        return $this->render('competition/create.html.twig', [
            'competition' => $competition,
            'competitionForm' => $competitionForm->createView(),
        ]);
    }

    /**
     * @Route("/competition/show/{competition}", name="show_competition")
     */
    public function showCompetition(Competition $competition, EntityManagerInterface $em): Response
    {
        $ratings = $em->getRepository(Rating::class)->findBy(['competition' => $competition], ['place' => 'ASC']);

        return $this->render('competition/show.html.twig', [
            'competition' => $competition,
            'ratings' => $ratings,
        ]);
    }

    /**
     * @Route("/competition/admin/{competition}", name="admin_competition")
     */
    public function adminCompetition(Request $request, Competition $competition, EntityManagerInterface $em): Response
    {
        $games = $em->getRepository(Game::class)->findBy(['competition' => $competition, 'isActive' => true]);
        $gameForms = [];
        foreach($games as $game){
            $gameId = $game->getId();
            $firstPlayer = $game->getFirstPlayer();
            $secondPlayer = $game->getSecondPlayer();

            $data = [
                'gameId' => $game->getId(),
                'firstPlayerName' => $firstPlayer->getFirstName(),
                'secondPlayerName' => $secondPlayer->getFirstName(),
            ];

            $gameForm  = $this->createForm(GameType::class, null, $data);
            $gameForm->handleRequest($request);
            $gameForms[$gameId] = $gameForm->createView();

            if($gameForm->isSubmitted() && $gameForm->isValid()){
                $choces = $gameForm->get("choices$gameId")->getData();

                $this->gameService->endGame($game, $firstPlayer, $secondPlayer, $choces);

                return $this->redirectToRoute('admin_competition', ['competition' => $competition->getId()]);
            }
        }

        //Форма создания игры

        $addGameForm = $this->createForm(AddGameType::class);
        $addGameForm->handleRequest($request);

        if ($addGameForm->isSubmitted() && $addGameForm->isValid()) {

            $data = $this->playerService->pickPlayer($competition);

            $data['competition'] = $competition;


            $this->gameService->createGame($data);

            return $this->redirectToRoute('admin_competition', ['competition' => $competition->getId()]);
        }

        //Форма завершения соревнования

        $endGameForm  = $this->createForm(EndGameType::class);
        $endGameForm->handleRequest($request);

        if ($endGameForm->isSubmitted() && $endGameForm->isValid()) {
            $this->competitionService->endCompetition($competition);

            return $this->redirectToRoute('admin_competition', ['competition' => $competition->getId()]);
        }

        return $this->render('competition/admin.html.twig', [
            'competition' => $competition,
            'addGameForm' => $addGameForm->createView(),
            'endGameForm' => $endGameForm->createView(),
            'gameForms' => $gameForms,
        ]);
    }

    /**
     * @Route("/competition/player/{competition}", name="player_competition")
     */
    public function playerCompetition(Competition $competition): Response
    {
        return $this->render('competition/show.html.twig', [
            'competition' => $competition,
        ]);
    }

    /**
     * @Route("/competition/add/player/{competition}", name="add_player")
     */
    public function addPlayer(Request $request, Competition $competition, EntityManagerInterface $em): Response
    {
        $addForm  = $this->createForm(AddPlayerType::class);
        $addForm->handleRequest($request);
        if ($addForm->isSubmitted() && $addForm->isValid()) {
            $hash = $addForm->get('code')->getData();
            $competitionHash = $competition->getCode();

            if ($hash == $competitionHash) {
                $user = $this->getUser();
                $this->playerService->addPlayer($competition, $user);

                return $this->redirectToRoute('show_competition', ['competition' => $competition->getId()]);
            }
        }

        return $this->render('competition/addPlayer.html.twig', [
            'competition' => $competition,
            'addForm' => $addForm->createView(),
        ]);
    }

    /**
     * @Route("/competition/history/{competition}", name="history_competition")
     */
    public function historyCompetition(Competition $competition, EntityManagerInterface $em): Response
    {
        if($competition->getIsActive() === true){
            return $this->redirectToRoute('competition');
        }
        return $this->render('competition/history.html.twig', [
            'competition' => $competition,
        ]);
    }
}
