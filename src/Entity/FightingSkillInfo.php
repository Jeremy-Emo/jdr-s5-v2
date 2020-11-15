<?php

namespace App\Entity;

use App\Repository\FightingSkillInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FightingSkillInfoRepository::class)
 */
class FightingSkillInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\OneToOne(targetEntity=Skill::class, inversedBy="fightingSkillInfo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Skill $skill = null;

    /**
     * @ORM\OneToMany(targetEntity=ElementMultiplier::class, mappedBy="skill", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private Collection $elementsMultipliers;

    /**
     * @ORM\ManyToMany(targetEntity=Element::class, inversedBy="fightingSkills", cascade={"persist", "remove"})
     */
    private Collection $element;

    /**
     * @ORM\OneToMany(targetEntity=FightingSkillBattleState::class, mappedBy="skill", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private Collection $battleStates;

    /**
     * @ORM\OneToMany(targetEntity=StatMultiplier::class, mappedBy="skill", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private Collection $statMultipliers;

    /**
     * @ORM\ManyToOne(targetEntity=BattleSkillCustomEffect::class, inversedBy="skillsWithThis")
     */
    private ?BattleSkillCustomEffect $customEffects;

    public function __construct()
    {
        $this->elementsMultipliers = new ArrayCollection();
        $this->element = new ArrayCollection();
        $this->battleStates = new ArrayCollection();
        $this->statMultipliers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * @return Collection|ElementMultiplier[]
     */
    public function getElementsMultipliers(): Collection
    {
        return $this->elementsMultipliers;
    }

    public function addElementsMultiplier(ElementMultiplier $elementMultiplier): self
    {
        if (!$this->elementsMultipliers->contains($elementMultiplier)) {
            $this->elementsMultipliers[] = $elementMultiplier;
            $elementMultiplier->setSkill($this);
        }

        return $this;
    }

    public function removeElementsMultiplier(ElementMultiplier $elementMultiplier): self
    {
        if ($this->elementsMultipliers->removeElement($elementMultiplier)) {
            // set the owning side to null (unless already changed)
            if ($elementMultiplier->getSkill() === $this) {
                $elementMultiplier->setSkill(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Element[]
     */
    public function getElement(): Collection
    {
        return $this->element;
    }

    public function addElement(Element $element): self
    {
        if (!$this->element->contains($element)) {
            $this->element[] = $element;
        }

        return $this;
    }

    public function removeElement(Element $element): self
    {
        $this->element->removeElement($element);

        return $this;
    }

    /**
     * @return Collection|FightingSkillBattleState[]
     */
    public function getBattleStates(): Collection
    {
        return $this->battleStates;
    }

    public function addBattleState(FightingSkillBattleState $battleState): self
    {
        if (!$this->battleStates->contains($battleState)) {
            $this->battleStates[] = $battleState;
            $battleState->setSkill($this);
        }

        return $this;
    }

    public function removeBattleState(FightingSkillBattleState $battleState): self
    {
        if ($this->battleStates->removeElement($battleState)) {
            // set the owning side to null (unless already changed)
            if ($battleState->getSkill() === $this) {
                $battleState->setSkill(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StatMultiplier[]
     */
    public function getStatMultipliers(): Collection
    {
        return $this->statMultipliers;
    }

    public function addStatMultiplier(StatMultiplier $statMultiplier): self
    {
        if (!$this->statMultipliers->contains($statMultiplier)) {
            $this->statMultipliers[] = $statMultiplier;
            $statMultiplier->setSkill($this);
        }

        return $this;
    }

    public function removeStatMultiplier(StatMultiplier $statMultiplier): self
    {
        if ($this->statMultipliers->removeElement($statMultiplier)) {
            // set the owning side to null (unless already changed)
            if ($statMultiplier->getSkill() === $this) {
                $statMultiplier->setSkill(null);
            }
        }

        return $this;
    }

    public function getCustomEffects(): ?BattleSkillCustomEffect
    {
        return $this->customEffects;
    }

    public function setCustomEffects(?BattleSkillCustomEffect $customEffects): self
    {
        $this->customEffects = $customEffects;

        return $this;
    }
}
