<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/user/show/{user}", name="user_show")
     */
    public function index(User $user, EntityManagerInterface $em): Response
    {
        $competitions = [];
        if ($user !== $this->getUser()) {
            return $this->redirectToRoute('competition');
        }
        $players = $em->getRepository(Player::class)->findBy(['users' => $user]);
        foreach($players as $player){
            $competitionId = $player->getCompetition()->getId();
            $competition = $em->getRepository(Competition::class)->find($competitionId);
            $competitions[$competitionId] = $competition;
        }
        return $this->render('user/show.html.twig', [
            'competitions' => $competitions,
        ]);
    }
}
