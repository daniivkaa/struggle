<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\PlayersGame;
use App\Entity\Rating;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{

    private $em;
    private $ratingRepository;

    public function __construct(EntityManagerInterface $em, RatingRepository $ratingRepository)
    {
        $this->em = $em;
        $this->ratingRepository = $ratingRepository;

    }

    public function createGame($data){
        $firstPlayer = $data['firstPlayer'];
        $secondPlayer = $data['secondPlayer'];
        $competition = $data['competition'];

        $game = new Game();
        $game->setCreatedAt(new \DateTime('now'));
        $game->setPartNumber(3);
        $game->setFirstPlayer($firstPlayer);
        $game->setSecondPlayer($secondPlayer);
        $game->setCompetition($competition);
        $game->setIsActive(true);

        $firstPlayer->setIsActive(true);
        $playersGameFirst = new PlayersGame();
        $playersGameFirst->setTargetPlayer($firstPlayer);
        $playersGameFirst->setSecondPlayer($secondPlayer);
        $playersGameFirst->setCompetition($competition);
        $playersGameFirst->setGame($game);
        $this->em->persist($playersGameFirst);

        $secondPlayer->setIsActive(true);
        $playersGameSecond = new PlayersGame();
        $playersGameSecond->setTargetPlayer($secondPlayer);
        $playersGameSecond->setSecondPlayer($firstPlayer);
        $playersGameSecond->setCompetition($competition);
        $playersGameSecond->setGame($game);
        $this->em->persist($playersGameSecond);

        $this->em->persist($game);
        $this->em->flush();
    }

    public function endGame($game, $firstPlayer, $secondPlayer, $choces){
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
        $this->em->persist($game);

        $firstPlayer->setIsActive(false);
        $this->em->persist($firstPlayer);

        $secondPlayer->setIsActive(false);
        $this->em->persist($secondPlayer);

        $rating = $this->ratingRepository->findOneBy(['player' => $winer]);
        $countWin = $rating->getCountWin() + 1;
        $rating->setCountWin($countWin);
        $this->em->persist($rating);

        $this->em->flush();
    }


}