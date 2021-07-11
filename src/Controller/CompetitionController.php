<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\PlayersGame;
use App\Entity\Rating;
use App\Entity\User;
use App\Form\CompetitionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CompetitionController extends AbstractController
{
    /**
     * @Route("/competition", name="competition")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $competition = $em->getRepository(Competition::class)->findBy([], ['id' => 'DESC']);
        return $this->render('competition/index.html.twig', [
            'competitions' => $competition,
        ]);
    }

    /**
     * @Route("/competition/create", name="create_competition")
     */
    public function createCompetition(Request $request, EntityManagerInterface $em): Response
    {
        $competition = new Competition();
        $competitionForm= $this->createForm(CompetitionType::class, $competition);
        $competitionForm->handleRequest($request);

        if ($competitionForm->isSubmitted() && $competitionForm->isValid()) {

            $competition = $competitionForm->getData();
            $competition->setCode(md5(time()));
            $competition->setIsActive(true);

            $em->persist($competition);
            $em->flush();

            return $this->redirectToRoute('show_competition', ['competition' => $competition->getId()]);
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

            $firstPlayerName = $firstPlayer->getFirstName();
            $secondPlayerName = $secondPlayer->getFirstName();

            $defaultData = ['message' => 'Type your message here'];
            $gameForm  = $this->createFormBuilder($defaultData)
                ->add("choices$gameId", ChoiceType::class, [
                    'choices' => [
                        $firstPlayerName => 1,
                        $secondPlayerName => 2,
                    ]
                ])
                ->add("save$gameId", SubmitType::class, ['label' => "End game $gameId"])
                ->getForm();
            $gameForm->handleRequest($request);
            $gameForms[$gameId] = $gameForm->createView();

            if($gameForm->isSubmitted() && $gameForm->isValid()){
                $choces = $gameForm->get("choices$gameId")->getData();
                $winer = Player::class;
                if($choces === 1){
                    $winer = $game->getFirstPlayer();
                    $game->setWiner($winer);
                }
                else if($choces === 2){
                    $winer = $game->getSecondPlayer();
                    $game->setWiner($winer);
                }
                $game->setIsActive(false);
                $game->setEndedAt(new \DateTime('now'));
                $em->persist($game);

                $firstPlayer->setIsActive(false);
                $em->persist($firstPlayer);

                $secondPlayer->setIsActive(false);
                $em->persist($secondPlayer);

                $rating = $em->getRepository(Rating::class)->findOneBy(['player' => $winer]);
                $countWin = $rating->getCountWin() + 1;
                $rating->setCountWin($countWin);
                $em->persist($rating);

                $em->flush();

                return $this->redirectToRoute('admin_competition', ['competition' => $competition->getId()]);
            }
        }

        $defaultData = ['message' => 'Type your message here'];
        $addGameForm  = $this->createFormBuilder($defaultData)
            ->add('save', SubmitType::class, ['label' => 'Create game'])
            ->getForm();
        $addGameForm->handleRequest($request);

        if ($addGameForm->isSubmitted() && $addGameForm->isValid()) {

            $players = $em->getRepository(Player::class)->findBy(['competition' => $competition, 'isActive' => false]);
            $countPlayer = count($em->getRepository(Player::class)->findBy(['competition' => $competition]));

            $firstPlayer = Player::class;
            $secondPlayer = Player::class;

            foreach($players as $player){
                $idPlayer = $player->getId();
                $countGames = count($em->getRepository(PlayersGame::class)->findBy(['targetPlayer' => $idPlayer]));
                if($countGames < $countPlayer){
                    $firstPlayer = $player;
                    break;
                }
            }

            foreach($players as $player){
                $idSecondPlayer = $player->getId();
                $idFirstPlayer = $firstPlayer->getId();
                if($player === $firstPlayer) {
                    continue;
                }
                $countGamesWidthPlayer = count($em->getRepository(PlayersGame::class)->findBy(['targetPlayer' => $idFirstPlayer, 'secondPlayer' => $idSecondPlayer]));
                if($countGamesWidthPlayer === 0){
                    $secondPlayer = $player;
                    break;
                }
            }

            //$firstPlayer = array_shift($players);
            //$secondPlayer = array_shift($players);

            $firstPlayer->setIsActive(true);
            $playersGameFirst = new PlayersGame();
            $playersGameFirst->setTargetPlayer($firstPlayer);
            $playersGameFirst->setSecondPlayer($secondPlayer);
            $em->persist($playersGameFirst);

            $secondPlayer->setIsActive(true);
            $playersGameSecond = new PlayersGame();
            $playersGameSecond->setTargetPlayer($secondPlayer);
            $playersGameSecond->setSecondPlayer($firstPlayer);
            $em->persist($playersGameSecond);

            $game = new Game();
            $game->setCreatedAt(new \DateTime('now'));
            $game->setPartNumber(3);
            $game->setFirstPlayer($firstPlayer);
            $game->setSecondPlayer($secondPlayer);
            $game->setCompetition($competition);
            $game->setIsActive(true);

            $em->persist($game);
            $em->flush();

            return $this->redirectToRoute('admin_competition', ['competition' => $competition->getId()]);
        }

        $defaultData = ['message' => 'Type your message here'];
        $endGameForm  = $this->createFormBuilder($defaultData)
            ->add('endSave', SubmitType::class, ['label' => 'End game'])
            ->getForm();
        $endGameForm->handleRequest($request);

        if ($endGameForm->isSubmitted() && $endGameForm->isValid()) {
            $ratings = $em->getRepository(Rating::class)->findBy(['competition' => $competition], ['countWin' => 'DESC']);
            $i = 1;
            foreach($ratings as $rating){
                $rating->setPlace($i);
                $em->persist($rating);
                $i++;
            }
            $em->flush();

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
        $player = new Player();
        $defaultData = ['message' => 'Type your message here'];
        $addForm  = $this->createFormBuilder($defaultData)
            ->add('code', TextType::class)
            ->getForm();
        $addForm->handleRequest($request);

        if ($addForm->isSubmitted() && $addForm->isValid()) {
            $hash = $addForm->get('code')->getData();
            $competitionHash = $competition->getCode();
            if ($hash == $competitionHash) {
                $user = $this->getUser();
                $player->setCompetition($competition);
                $player->setFirstName($user->getFirstName());
                $player->setLastName($user->getLastName());
                $player->setPatronymic($user->getPatronymic());
                $player->setIsActive(false);
                $player->setUsers($user);
                $em->persist($player);

                $rating = new Rating();
                $rating->setPlayer($player);
                $rating->setCompetition($competition);
                $rating->setCountWin(0);
                $em->persist($rating);

                $em->flush();

                return $this->redirectToRoute('show_competition', ['competition' => $competition->getId()]);
            }
        }

        return $this->render('competition/addPlayer.html.twig', [
            'competition' => $competition,
            'addForm' => $addForm->createView(),
        ]);
    }
}
