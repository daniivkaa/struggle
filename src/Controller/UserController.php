<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Competition;
use App\Entity\Friend;
use App\Entity\Game;
use App\Entity\Message;
use App\Entity\Player;
use App\Entity\PlayersGame;
use App\Entity\Rating;
use App\Entity\User;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/user/history/{user}", name="user_history")
     */
    public function history(User $user, EntityManagerInterface $em): Response
    {

        $competitions = [];
        if ($user !== $this->getUser()) {
            return $this->redirectToRoute('competition');
        }
        $players = $em->getRepository(Player::class)->findBy(['users' => $user], ['id' => 'DESC']);
        foreach($players as $player){
            $competitionId = $player->getCompetition()->getId();
            $competition = $em->getRepository(Competition::class)->find($competitionId);
            $competitions[$competitionId]['competition'] = $competition;

            $rating = $em->getRepository(Rating::class)->findOneBy(['player' => $player]);
            $competitions[$competitionId]['rating'] = $rating;
        }
        return $this->render('user/history.html.twig', [
            'competitions' => $competitions,
        ]);
    }

    /**
     * @Route("/user/history/games/{competition}", name="user_history_games")
     */
    public function gameHistory(Competition $competition, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('competition');
        }

        $games = $em->getRepository(Game::class)->findBy(['competition' => $competition]);

        return $this->render('user/game_history.html.twig', [
            'games' => $games,
        ]);
    }

    /**
     * @Route("/user/show/{secondUser}", name="user_show")
     */
    public function show(User $secondUser, EntityManagerInterface $em): Response
    {
        $countCompetition = count($secondUser->getPlayers());
        return $this->render('user/show.html.twig', [
            'secondUser' => $secondUser,
            'countCompetition' => $countCompetition,
        ]);
    }

    /**
     * @Route("/user/friends/{user}", name="friends_show")
     */
    public function friendsShow(User $user, EntityManagerInterface $em): Response
    {
        $friends = $em->getRepository(Friend::class)->findBy(["targetUser" => $user]);

        return $this->render('user/friends.html.twig', [
            "friends" => $friends,
        ]);
    }

    /**
     * @Route("/user/profile/{user}", name="user_profile")
     */
    public function profile(User $user, EntityManagerInterface $em): Response
    {
        return $this->render('user/profile.html.twig', [
            "user" => $user,
        ]);
    }

    /**
     * @Route("/user/index", name="user_index")
     */
    public function index(EntityManagerInterface $em){

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('user/index.html.twig', [
            "users" => $users,
        ]);
    }
}
