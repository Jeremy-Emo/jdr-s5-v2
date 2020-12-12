<?php

namespace App\Entity;

use App\Repository\BattleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BattleRepository::class)
 */
class Battle
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
     * @ORM\ManyToOne(targetEntity=Party::class, inversedBy="battles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $party;

    /**
     * @ORM\ManyToMany(targetEntity=Monster::class, inversedBy="battles")
     */
    private $monsters;

    /**
     * @ORM\OneToMany(targetEntity=BattleTurn::class, mappedBy="battle", orphanRemoval=true)
     */
    private $turns;

    public function __construct()
    {
        $this->monsters = new ArrayCollection();
        $this->turns = new ArrayCollection();
    }

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

    public function getParty(): ?Party
    {
        return $this->party;
    }

    public function setParty(?Party $party): self
    {
        $this->party = $party;

        return $this;
    }

    /**
     * @return Collection|Monster[]
     */
    public function getMonsters(): Collection
    {
        return $this->monsters;
    }

    public function addMonster(Monster $monster): self
    {
        if (!$this->monsters->contains($monster)) {
            $this->monsters[] = $monster;
        }

        return $this;
    }

    public function removeMonster(Monster $monster): self
    {
        $this->monsters->removeElement($monster);

        return $this;
    }

    /**
     * @return Collection|BattleTurn[]
     */
    public function getTurns(): Collection
    {
        return $this->turns;
    }

    public function addTurn(BattleTurn $turn): self
    {
        if (!$this->turns->contains($turn)) {
            $this->turns[] = $turn;
            $turn->setBattle($this);
        }

        return $this;
    }

    public function removeTurn(BattleTurn $turn): self
    {
        if ($this->turns->removeElement($turn)) {
            // set the owning side to null (unless already changed)
            if ($turn->getBattle() === $this) {
                $turn->setBattle(null);
            }
        }

        return $this;
    }
}
