<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $winerScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $loserScore;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     */
    private $winer;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     */
    private $loser;

    /**
     * @ORM\OneToOne(targetEntity=Game::class, cascade={"persist", "remove"})
     */
    private $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWinerScore(): ?int
    {
        return $this->winerScore;
    }

    public function setWinerScore(int $winerScore): self
    {
        $this->winerScore = $winerScore;

        return $this;
    }

    public function getLoserScore(): ?int
    {
        return $this->loserScore;
    }

    public function setLoserScore(int $loserScore): self
    {
        $this->loserScore = $loserScore;

        return $this;
    }

    public function getWiner(): ?Player
    {
        return $this->winer;
    }

    public function setWiner(?Player $winer): self
    {
        $this->winer = $winer;

        return $this;
    }

    public function getLoser(): ?Player
    {
        return $this->loser;
    }

    public function setLoser(?Player $loser): self
    {
        $this->loser = $loser;

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
