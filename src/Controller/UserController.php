<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Competition;
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
        $players = $em->getRepository(Player::class)->findBy(['users' => $user]);
        foreach($players as $player){
            $competitionId = $player->getCompetition()->getId();
            $competition = $em->getRepository(Competition::class)->find($competitionId);
            $competitions[$competitionId]['competition'] = $competition;

            $playersGame = $em->getRepository(PlayersGame::class)->findBy(['targetPlayer' => $player]);
            $competitions[$competitionId]['playersGame'] = $playersGame;

            $rating = $em->getRepository(Rating::class)->findOneBy(['player' => $player]);
            $competitions[$competitionId]['rating'] = $rating;
        }
        return $this->render('user/profile.html.twig', [
            'competitions' => $competitions,
        ]);
    }

    /**
     * @Route("/user/show/{secondUser}", name="user_show")
     */
    public function show(User $secondUser, EntityManagerInterface $em): Response
    {

        return $this->render('user/show.html.twig', [
            'secondUser' => $secondUser,
        ]);
    }
}
