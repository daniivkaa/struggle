<?php

namespace App\Controller\Ajax;

use App\Entity\Competition;
use App\Entity\Game;
use App\Repository\GameRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompetitionController extends AbstractController
{
    private $gameRepository;

    public function __construct(GameRepositoryInterface $gameRepository, EntityManagerInterface $em){
        $this->gameRepository = $gameRepository;
    }

    /**
     * @Route("/competition/player/ajax/{competition}", name="player_ajax_competition", methods={"GET","POST"})
     */
    public function playerCompetitionAction(Competition $competition): JsonResponse
    {
        $games = $this->gameRepository->getGamesForAjax($competition->getId());

        return $this->json($games);
    }
}