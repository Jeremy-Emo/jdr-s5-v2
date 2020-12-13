<?php

namespace App\Entity;

use App\Repository\BattleTurnRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BattleTurnRepository::class)
 */
class BattleTurn
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Battle::class, inversedBy="turns")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Battle $battle;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $action;

    /**
     * @ORM\Column(type="json")
     */
    private array $battleState = [];

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $turnNumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBattle(): ?Battle
    {
        return $this->battle;
    }

    public function setBattle(?Battle $battle): self
    {
        $this->battle = $battle;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getBattleState(): ?array
    {
        return $this->battleState;
    }

    public function setBattleState(array $battleState): self
    {
        $this->battleState = $battleState;

        return $this;
    }

    public function getTurnNumber(): ?int
    {
        return $this->turnNumber;
    }

    public function setTurnNumber(int $turnNumber): self
    {
        $this->turnNumber = $turnNumber;

        return $this;
    }
}
