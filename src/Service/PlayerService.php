<?php

namespace App\Service;

use App\Entity\Competition;
use App\Entity\Notice;
use App\Entity\Player;
use App\Entity\Rating;
use App\Entity\User;
use App\Repository\GameRepositoryInterface;
use App\Repository\PlayerRepository;
use App\Repository\PlayersGameRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlayerService
{
    private $em;
    private $playerRepository;
    private $playersGameRepository;

    private $gameRepository;

    public function __construct(EntityManagerInterface $em, RatingRepository $ratingRepository, PlayerRepository $playerRepository, PlayersGameRepository $playersGameRepository, GameRepositoryInterface $gameRepository)
    {
        $this->em = $em;
        $this->playerRepository = $playerRepository;
        $this->playersGameRepository = $playersGameRepository;
        $this->gameRepository = $gameRepository;
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

    public function pickPlayer ($competition)
    {
        if ("circle" == $competition->getType()) {
            return $this->pickPlayerCircle($competition);
        } else {
            return $this->pickPlayerOlimp($competition);
        }
    }

    private function pickPlayerCircle ($competition)
    {
        $players = $this->playerRepository->findBy(['competition' => $competition, 'isActive' => false]);
        $countPlayer = count($this->playerRepository->findBy(['competition' => $competition]));

        $firstPlayer = null;
        $secondPlayer = null;

        if (!$players) {
            return null;
        }

        foreach ($players as $player) {
            $idPlayer = $player->getId();
            $countGames = count($this->playersGameRepository->findBy(['targetPlayer' => $idPlayer]));
            if ($countGames < $countPlayer - 1) {
                $firstPlayer = $player;
                break;
            }
        }

        if (!$firstPlayer) {
            return null;
        }

        foreach ($players as $player) {
            $idSecondPlayer = $player->getId();
            $idFirstPlayer = $firstPlayer->getId();
            if ($player === $firstPlayer) {
                continue;
            }
            $countGamesWidthPlayer = count($this->playersGameRepository->findBy(['targetPlayer' => $idFirstPlayer, 'secondPlayer' => $idSecondPlayer]));
            if ($countGamesWidthPlayer === 0) {
                $secondPlayer = $player;
                break;
            }
        }

        $data = null;

        if($firstPlayer && $secondPlayer){
            $data = [
                'firstPlayer' => $firstPlayer,
                'secondPlayer' => $secondPlayer,
            ];
        }

        return $data;
    }

    private function pickPlayerOlimp ($competition)
    {
        $players = $this->playerRepository->findBy(['competition' => $competition, 'isActive' => false]);

        $firstPlayer = null;
        $secondPlayer = null;

        if (!$players) {
            return null;
        }

        $targetPalyers = [];
        $maxCount = 0;

        foreach ($players as $player) {
            $idPlayer = $player->getId();
            $games = count($this->playersGameRepository->findBy(['targetPlayer' => $idPlayer]));
            $winnerGames = count($this->gameRepository->findBy(['winer' => $idPlayer]));
            if ($games == $winnerGames) {
                if ($games) {
                    $targetPalyers[$games][] = $player;
                    $maxCount = max($games, $maxCount);
                } else {
                    $targetPalyers[0][] = $player;
                }
            }
        }

        $i = 0;
        while ($i <= $maxCount) {
            if (!array_key_exists($i, $targetPalyers)) {
                $i++;
                continue;
            }
            $firstPlayer = $targetPalyers[$i][0];
            break;
        }

        if (!$firstPlayer) {
            return null;
        }

        $i = 0;
        while ($i <= $maxCount) {
            if (!array_key_exists($i, $targetPalyers)) {
                $i++;
                continue;
            }

            foreach ($targetPalyers[$i] as $player) {
                if ($player == $firstPlayer) {
                    continue;
                }
                $secondPlayer = $player;
                break 2;
            }
            $i++;
        }

        $data = null;

        if ($firstPlayer && $secondPlayer) {
            $data = [
                'firstPlayer' => $firstPlayer,
                'secondPlayer' => $secondPlayer,
            ];
        }

        return $data;
    }
}