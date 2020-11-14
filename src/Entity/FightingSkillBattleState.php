<?php

namespace App\Entity;

use App\Repository\FightingSkillBattleStateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FightingSkillBattleStateRepository::class)
 */
class FightingSkillBattleState
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToMany(targetEntity=BattleState::class, inversedBy="fightingSkills")
     */
    private Collection $states;

    /**
     * @ORM\ManyToOne(targetEntity=FightingSkillInfo::class, inversedBy="battleStates")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?FightingSkillInfo $skill;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $turnsNumber;

    public function __construct()
    {
        $this->states = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|BattleState[]
     */
    public function getStates(): Collection
    {
        return $this->states;
    }

    public function addState(BattleState $state): self
    {
        if (!$this->states->contains($state)) {
            $this->states[] = $state;
        }

        return $this;
    }

    public function removeState(BattleState $state): self
    {
        $this->states->removeElement($state);

        return $this;
    }

    public function getSkill(): ?FightingSkillInfo
    {
        return $this->skill;
    }

    public function setSkill(?FightingSkillInfo $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getTurnsNumber(): ?int
    {
        return $this->turnsNumber;
    }

    public function setTurnsNumber(int $turnsNumber): self
    {
        $this->turnsNumber = $turnsNumber;

        return $this;
    }
}
