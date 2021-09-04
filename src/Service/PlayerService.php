<?php

namespace App\Service;

use App\Entity\Competition;
use App\Entity\Notice;
use App\Entity\Player;
use App\Entity\Rating;
use App\Entity\User;
use App\Repository\PlayerRepository;
use App\Repository\PlayersGameRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlayerService
{
    private $em;
    private $playerRepository;
    private $playersGameRepository;

    public function __construct(EntityManagerInterface $em, RatingRepository $ratingRepository, PlayerRepository $playerRepository, PlayersGameRepository $playersGameRepository)
    {
        $this->em = $em;
        $this->playerRepository = $playerRepository;
        $this->playersGameRepository = $playersGameRepository;
    }

    public function addPlayer(Competition $competition, User $user)
    {
        $player = new Player();
        $player->setCompetition($competition);
        $player->setFirstName($user->getFirstName());
        $player->setLastName($user->getLastName());
        $player->setPatronymic($user->getPatronymic());
        $player->setIsActive(false);
        $player->setUsers($user);
        $this->em->persist($player);

        $rating = new Rating();
        $rating->setPlayer($player);
        $rating->setCompetition($competition);
        $rating->setCountWin(0);
        $this->em->persist($rating);

        $notice = new Notice();
            $notice->setType("competition");
            $notice->setTargetUser($user);
            $notice->setCompetition($competition);
            $notice->setActive(true);
        $this->em->persist($notice);

        $this->em->flush();

        return $player;
    }

    public function pickPlayer($competition){
        $players = $this->playerRepository->findBy(['competition' => $competition, 'isActive' => false]);
        $countPlayer = count($this->playerRepository->findBy(['competition' => $competition]));

        $firstPlayer = Player::class;
        $secondPlayer = Player::class;

        foreach($players as $player){
            $idPlayer = $player->getId();
            $countGames = count($this->playersGameRepository->findBy(['targetPlayer' => $idPlayer]));
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
            $countGamesWidthPlayer = count($this->playersGameRepository->findBy(['targetPlayer' => $idFirstPlayer, 'secondPlayer' => $idSecondPlayer]));
            if($countGamesWidthPlayer === 0){
                $secondPlayer = $player;
                break;
            }
        }

        $data = [
            'firstPlayer' => $firstPlayer,
            'secondPlayer' => $secondPlayer,
        ];

        return $data;
    }

}