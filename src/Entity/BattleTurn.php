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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Battle::class, inversedBy="turns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $battle;

    /**
     * @ORM\Column(type="text")
     */
    private $action;

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
}
