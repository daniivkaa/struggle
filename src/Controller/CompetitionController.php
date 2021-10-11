<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Friend;
use App\Entity\Game;
use App\Entity\Notice;
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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
        $user = $this->getUser();
        $competition = $em->getRepository(Competition::class)->findBy(['public' => true, "isActive" => true], ['id' => 'DESC']);
        return $this->render('competition/index.html.twig', [
            'competitions' => $competition,
        ]);
    }

    /**
     * @Route("/competition/create", name="create_competition")
     */
    public function createCompetition(Request $request, EntityManagerInterface $em): Response
    {
        $lider = $this->getUser();
        $competition = new Competition();
        $competitionForm= $this->createForm(CompetitionType::class, $competition);
        $competitionForm->handleRequest($request);

        if ($competitionForm->isSubmitted() && $competitionForm->isValid()) {
            $this->competitionService->createCompetition($competition, $lider);

            $notice = new Notice();
                $notice->setType("admin_competition");
                $notice->setTargetUser($lider);
                $notice->setCompetition($competition);
                $notice->setActive(true);

            $em->persist($notice);
            $em->flush();

            return $this->redirectToRoute('admin_competition_game', ['competition' => $competition->getId()]);
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
     * @Route("/competition/admin/games/{competition}", name="admin_competition_game")
     */
    public function adminCompetitionGames(Request $request, Competition $competition, EntityManagerInterface $em): Response
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

                return $this->redirectToRoute('admin_competition_game', ['competition' => $competition->getId()]);
            }
        }

        //Форма создания игры

        $addGameForm = $this->createForm(AddGameType::class);
        $addGameForm->handleRequest($request);

        if ($addGameForm->isSubmitted() && $addGameForm->isValid()) {

            $data = $this->playerService->pickPlayer($competition);
            if($data){
                $data['competition'] = $competition;

                $this->gameService->createGame($data);
            }

            return $this->redirectToRoute('admin_competition_game', ['competition' => $competition->getId()]);
        }

        return $this->render('competition/lider/games.html.twig', [
            'competition' => $competition,
            'addGameForm' => $addGameForm->createView(),
            'gameForms' => $gameForms,
        ]);
    }

    /**
     * @Route("/competition/admin/players/{competition}", name="admin_competition_players")
     */
    public function adminCompetitionPlayers(Competition $competition, EntityManagerInterface $em): Response
    {
        $players = $em->getRepository(Player::class)->findBy(["competition" => $competition]);

        return $this->render('competition/lider/players.html.twig', [
            'competition' => $competition,
            'players' => $players,
        ]);
    }

    /**
     * @Route("/competition/admin/profile/{competition}", name="admin_competition_profile")
     */
    public function adminCompetitionProfile(Competition $competition, EntityManagerInterface $em): Response
    {
        return $this->render('competition/lider/profile.html.twig', [
            'competition' => $competition,
        ]);
    }

    /**
     * @Route("/competition/admin/end/{competition}", name="admin_competition_end")
     */
    public function adminCompetitionEnd(Competition $competition, EntityManagerInterface $em): Response
    {
        $this->competitionService->endCompetition($competition);

        $notices = $em->getRepository(Notice::class)->findBy(["competition" => $competition]);
        foreach($notices as $notice){
            $notice->setActive(false);
            $em->persist($notice);
        }

        $em->flush();

        return $this->redirectToRoute('admin_competition_game', ['competition' => $competition->getId()]);
    }

    /**
     * @Route("/competition/player/{competition}", name="player_competition")
     */
    public function playerCompetition(Competition $competition, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $player = $em->getRepository(Player::class)->findOneBy(["users" => $user, "competition" => $competition]);

        return $this->render('competition/player.html.twig', [
            'competition' => $competition,
            'player' => $player,
        ]);
    }

    /**
     * @Route("/competition/add/player", name="add_player")
     */
    public function addPlayer(Request $request, EntityManagerInterface $em): Response
    {
        $addForm  = $this->createForm(AddPlayerType::class, null, [
            'action' => $this->generateUrl('add_player'),
            'method' => 'POST',
        ]);
        $addForm->handleRequest($request);
        if ($addForm->isSubmitted() && $addForm->isValid()) {
            $hash = $addForm->get('code')->getData();
            $competition = $em->getRepository(Competition::class)->findOneBy(["code" => $hash]);

            if ($competition) {
                $user = $this->getUser();
                $player = $this->playerService->addPlayer($competition, $user);

                return $this->redirectToRoute('player_competition', ['competition' => $competition->getId()]);
            }
        }

        return $this->render('competition/addPlayer.html.twig', [
            'addForm' => $addForm->createView(),
        ]);
    }

    /**
     * @Route("/competition/invite/users/{competition}", name="invite_users")
     */
    public function inviteUsers(Competition $competition, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $friends = $em->getRepository(Friend::class)->findBy(["targetUser" => $user]);

        $formFriends = [];
        foreach($friends as $friend){
            $key = $friend->getSecondUser()->getId();
            $name = $friend->getSecondUser()->getFirstName();
            $lastName = $friend->getSecondUser()->getLastName();
            $patronymic = $friend->getSecondUser()->getPatronymic();

            $formFriends["$name $lastName $patronymic"] = $key;
        }

        $form = $this->createFormBuilder()
            ->add("checkbox", ChoiceType::class, [
                'choices' => $formFriends,
                'expanded' => true,
                'multiple'=> true
            ])

        ->add('save', SubmitType::class, ['label' => 'Пригласить'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            foreach($data["checkbox"] as $userId){
                $targetUser = $em->getRepository(User::class)->findOneBy(["id" => $userId]);

                $isNotice = $em->getRepository(Notice::class)->findOneBy(["type" => "invite_competition", "targetUser" => $targetUser, "competition" => $competition]);

                if($isNotice){
                    continue;
                }

                $notice = new Notice();
                    $notice->setType("invite_competition");
                    $notice->setTargetUser($targetUser);
                    $notice->setCompetition($competition);
                    $notice->setActive(true);

                $em->persist($notice);
                $em->flush();
            }

            return $this->redirectToRoute('admin_competition_players', ['competition' => $competition->getId()]);
        }

        return $this->render('competition/lider/inviteUsers.html.twig', [
            'competition' => $competition,
            "inviteForm" => $form->createView(),
        ]);
    }

    /**
     * @Route("/competition/connect/invite/{competition}", name="connect_competition_invite")
     */
    public function connectCompetitionToInvite(Competition $competition, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $notice = $em->getRepository(Notice::class)->findOneBy(["type" => "invite_competition", "targetUser" => $user, "competition" => $competition, "active" => true]);

        if($notice){
            $notice->setActive(false);
            $em->persist($notice);
            $em->flush();

            $player = $this->playerService->addPlayer($competition, $user);

            return $this->redirectToRoute('player_competition', ['competition' => $competition->getId()]);
        }

        return $this->redirectToRoute('notice');
    }

    /**
     * @Route("/competition/connect/public/{competition}", name="connect_competition_public")
     */
    public function connectCompetitionPublic(Competition $competition, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $isPlayer = $em->getRepository(Player::class)->findOneBy(["competition" => $competition, "users" => $user]);

        if($competition->getPublic() && !$isPlayer){

            $player = $this->playerService->addPlayer($competition, $user);

            return $this->redirectToRoute('player_competition', ['competition' => $competition->getId()]);
        }

        return $this->redirectToRoute('competition');
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

    /**
     * @Route("/competition/ended", name="show_ended_competition")
     */
    public function showEndedCompetition(EntityManagerInterface $em): Response
    {
        $competitions = $em->getRepository(Competition::class)->findBy(["isActive" => false, "public" => true]);

        return $this->render('competition/ended.html.twig', [
            'competitions' => $competitions,
        ]);
    }
}
