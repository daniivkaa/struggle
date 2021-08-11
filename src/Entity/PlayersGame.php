<?php

namespace App\Entity;

use App\Repository\PlayersGameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayersGameRepository::class)
 */
class PlayersGame
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     */
    private $targetPlayer;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     */
    private $secondPlayer;

    /**
     * @ORM\ManyToOne(targetEntity=Competition::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $competition;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTargetPlayer(): ?Player
    {
        return $this->targetPlayer;
    }

    public function setTargetPlayer(?Player $targetPlayer): self
    {
        $this->targetPlayer = $targetPlayer;

        return $this;
    }

    public function getSecondPlayer(): ?Player
    {
        return $this->secondPlayer;
    }

    public function setSecondPlayer(?Player $secondPlayer): self
    {
        $this->secondPlayer = $secondPlayer;

        return $this;
    }

    public function getCompetition(): ?Competition
    {
        return $this->competition;
    }

    public function setCompetition(?Competition $competition): self
    {
        $this->competition = $competition;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
