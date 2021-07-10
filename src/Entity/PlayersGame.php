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
}
