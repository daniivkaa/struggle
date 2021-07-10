<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $partNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="games")
     */
    private $firstPlayer;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="games")
     */
    private $secondPlayer;

    /**
     * @ORM\ManyToOne(targetEntity=Competition::class, inversedBy="games")
     */
    private $competition;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class)
     */
    private $winer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->endedAt;
    }

    public function setEndedAt(\DateTimeInterface $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getPartNumber(): ?int
    {
        return $this->partNumber;
    }

    public function setPartNumber(int $partNumber): self
    {
        $this->partNumber = $partNumber;

        return $this;
    }

    public function getFirstPlayer(): ?Player
    {
        return $this->firstPlayer;
    }

    public function setFirstPlayer(?Player $firstPlayer): self
    {
        $this->firstPlayer = $firstPlayer;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
}
