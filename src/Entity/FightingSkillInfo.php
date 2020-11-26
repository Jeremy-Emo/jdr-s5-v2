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
    public function generateDescription(?int $level = 1): string
    {
        $full = "";

        $elements = "";
        foreach ($this->getElement() as $element) {
            $elements .= $element->getName();
            if ($element !== $this->getElement()->last()) {
                $elements .= " & ";
            }
        }
        if ($elements !== "") {
            $full.= "<p>Élément(s) :  " . $elements . "</p>";
        }

        if ($this->isCriticalRateUpgraded) {
            $full .= "<p>Taux critique augmenté.</p>";
        }
        if ($this->isIgnoreDefense) {
            $full .= "<p>Ignore la défense de l'ennemi</p>";
        }
        if ($this->isAoE) {
            $full .= "<p>Compétence de zone</p>";
        }

        if ($this->customEffects !== null) {
            $full .= "<p>" . $this->customEffects . "</p>";
        }

        if ($this->accuracy !== null) {
            $full .= "<p>Chances de toucher : " . ($this->accuracy * $level) . "%</p>";
        }

        if ($this->getElementsMultipliers()->count() > 0) {
            $damages = "";
            $res = "";
            foreach ($this->getElementsMultipliers() as $mult) {
                if ($mult->getIsResistance()) {
                    $res .= "<li>" . $mult->getElement()->getName() . " : " . ($mult->getValue() * $level) . "%</li>";
                } else {
                    $damages .= "<li>" . $mult->getElement()->getName() . " : " . ($mult->getValue() * $level) . "%</li>";
                }
            }
            if ($damages !== "") {
                $full .= "<p>Multiplicateurs élémentaux :</p><ul>";
                $full .= $damages;
                $full .= "</ul>";
            }
            if ($res !== "") {
                $full .= "<p>Résistances élémentaires :</p><ul>";
                $full .= $res;
                $full .= "</ul>";
            }
        }

        if ($this->getStatMultipliers()->count() > 0) {
            $full .= "<p>Multiplicateurs de dégâts :</p><ul>";
            foreach ($this->getStatMultipliers() as $mult) {
                $full .= "<li>" . $mult->getStat()->getName() . " : " . ($mult->getValue() * $level) . "%</li>";
            }
            $full .= "</ul>";
        }

        foreach ($this->getBattleStates() as $states) {
            $add = "Pose ";
            foreach ($states->getStates() as $state) {
                $add .= $state->getName();
                if ($state !== $states->getStates()->last()) {
                    $add .= ", ";
                }
            }

            if ($states->getTurnsNumber() !== null) {
                $add .= " pendant " . $states->getTurnsNumber() . " tour(s)";
            }
            $full .= "<p>" . $add . "</p>";
        }

        if ($this->needWeaponType !== null) {
            $full .= "<p class='red'>Nécessite une arme de type : " . $this->needWeaponType . "</p>";
        }

        return $full;
    }

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
     * @ORM\ManyToOne(targetEntity=CustomEffect::class, inversedBy="skillsWithThis")
     */
    private ?CustomEffect $customEffects;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isCriticalRateUpgraded = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isIgnoreDefense = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $accuracy;

    /**
     * @ORM\ManyToOne(targetEntity=WeaponType::class, inversedBy="fightingSkillInfos")
     */
    private ?WeaponType $needWeaponType;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isAoE = false;

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

    public function getCustomEffects(): ?CustomEffect
    {
        return $this->customEffects;
    }

    public function setCustomEffects(?CustomEffect $customEffects): self
    {
        $this->customEffects = $customEffects;

        return $this;
    }

    public function getIsCriticalRateUpgraded(): ?bool
    {
        return $this->isCriticalRateUpgraded;
    }

    public function setIsCriticalRateUpgraded(bool $isCriticalRateUpgraded): self
    {
        $this->isCriticalRateUpgraded = $isCriticalRateUpgraded;

        return $this;
    }

    public function getIsIgnoreDefense(): ?bool
    {
        return $this->isIgnoreDefense;
    }

    public function setIsIgnoreDefense(bool $isIgnoreDefense): self
    {
        $this->isIgnoreDefense = $isIgnoreDefense;

        return $this;
    }

    public function getAccuracy(): ?int
    {
        return $this->accuracy;
    }

    public function setAccuracy(?int $accuracy): self
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getNeedWeaponType(): ?WeaponType
    {
        return $this->needWeaponType;
    }

    public function setNeedWeaponType(?WeaponType $needWeaponType): self
    {
        $this->needWeaponType = $needWeaponType;

        return $this;
    }

    public function getIsAoE(): ?bool
    {
        return $this->isAoE;
    }

    public function setIsAoE(bool $isAoE): self
    {
        $this->isAoE = $isAoE;

        return $this;
    }
}
