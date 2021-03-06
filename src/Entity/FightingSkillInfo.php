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
    public function generateDescription(?int $level = 1, ?bool $needFullDescription = true): string
    {
        if ($level === 0) {
            $level = 1;
        }

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

        if (!empty($this->drainLife)) {
            $full .= "<p>Drain de vie : " . $this->drainLife . "%</p>";
        }

        if (!empty($this->criticalDamages)) {
            $full .= "<p>Dégâts critiques bonus : " . $this->criticalDamages . "%</p>";
        }

        if ($this->customEffects !== null) {
            $full .= "<p>Effet spécial : " . $this->customEffects;
            if (!empty($this->customEffects->getAdvancedDescription())) {
                $full .= "<br>" . $this->customEffects->getAdvancedDescription();
            }
            $full.= "</p>";
        }

        if ($this->accuracy !== null) {
            $full .= "<p>Chances de toucher : " . ($this->accuracy * $level) . "%</p>";
        }

        if ($this->isOnSelfOnly) {
        $full .= "<p>Ne peut cibler que soi.</p>";
        }

        if ($this->isShield) {
            $full .= "<p>Crée un bouclier.</p>";
        }

        if ($this->isIgnoreShield) {
            $full .= "<p>Ignore les boucliers.</p>";
        }

        if ($this->isResurrection) {
            $full .= "<p>Ramène à la vie.</p>";
        }

        if ($this->isCleanse) {
            $full .= "<p>Retire tous les statuts.</p>";
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
            if ($this->isHeal) {
                $full .= "<p>Soin :</p><ul>";
            } elseif($this->isShield) {
                $full .= "<p>Protection :</p><ul>";
            } else {
                $full .= "<p>Multiplicateurs de dégâts :</p><ul>";
            }
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

        if ($this->needWeaponType !== null && $needFullDescription) {
            $full .= "<p class='red'>Nécessite une arme de type : " . $this->needWeaponType . "</p>";
        }

        if ($this->needStatusToCast->count() > 0 && $needFullDescription) {
            $full .= "<p>Nécessite d'être affecté par ";
            foreach ($this->needStatusToCast as $status) {
                $full .= $status;
                if ($status !== $this->needStatusToCast->last()) {
                    $full .= " & ";
                }
            }
            $full .= " pour être lancé.</p>";
        }

        if ($this->castingTime > 0) {
            $full .= "<p>Temps d'incantation : " . $this->castingTime . " tour(s)</p>";
        }

        if (!empty($this->boostAtb)) {
            $full .= "<p>Booste l'atb de " . $this->boostAtb . " %</p>";
        }

        if (!empty($this->autoAtbBoost)) {
            $full .= "<p>Booste votre atb de " . $this->autoAtbBoost . " %</p>";
        }

        if (!empty($this->reducAtb)) {
            $full .= "<p>Réduit l'atb de " . $this->reducAtb . " %</p>";
        }

        if ($this->isReplayAttack) {
            $full .= "<p>Rejoue un tour après celui-ci.</p>";
        }

        if (!empty($this->healSP)) {
            $full .= "<p>Soigne la fatigue de " . $this->healSP . "</p>";
        }

        if (!empty($this->hitSP)) {
            $full .= "<p>Inflige " . $this->hitSP . " de fatigue</p>";
        }

        if (!empty($this->healMP)) {
            $full .= "<p>Restaure le mana de " . $this->healMP . "</p>";
        }

        if (!empty($this->hitMP)) {
            $full .= "<p>Inflige " . $this->hitMP . " de mana</p>";
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

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isOnSelfOnly = false;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $castingTime;

    /**
     * @ORM\ManyToMany(targetEntity=BattleState::class)
     */
    private Collection $needStatusToCast;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isHeal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $drainLife = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $criticalDamages = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isShield = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isResurrection;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isIgnoreShield;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isCleanse = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $boostAtb = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $reducAtb = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $healSP = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $hitSP = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $healMP = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $hitMP = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isReplayAttack = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $autoAtbBoost;

    public function __construct()
    {
        $this->elementsMultipliers = new ArrayCollection();
        $this->element = new ArrayCollection();
        $this->battleStates = new ArrayCollection();
        $this->statMultipliers = new ArrayCollection();
        $this->needStatusToCast = new ArrayCollection();
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

    public function getIsOnSelfOnly(): ?bool
    {
        return $this->isOnSelfOnly;
    }

    public function setIsOnSelfOnly(bool $isOnSelfOnly): self
    {
        $this->isOnSelfOnly = $isOnSelfOnly;

        return $this;
    }

    public function getCastingTime(): ?int
    {
        return $this->castingTime;
    }

    public function setCastingTime(int $castingTime): self
    {
        $this->castingTime = $castingTime;

        return $this;
    }

    /**
     * @return Collection|BattleState[]
     */
    public function getNeedStatusToCast(): Collection
    {
        return $this->needStatusToCast;
    }

    public function addNeedStatusToCast(BattleState $needStatusToCast): self
    {
        if (!$this->needStatusToCast->contains($needStatusToCast)) {
            $this->needStatusToCast[] = $needStatusToCast;
        }

        return $this;
    }

    public function removeNeedStatusToCast(BattleState $needStatusToCast): self
    {
        $this->needStatusToCast->removeElement($needStatusToCast);

        return $this;
    }

    public function getIsHeal(): ?bool
    {
        return $this->isHeal;
    }

    public function setIsHeal(bool $isHeal): self
    {
        $this->isHeal = $isHeal;

        return $this;
    }

    public function getDrainLife(): ?int
    {
        return $this->drainLife;
    }

    public function setDrainLife(?int $drainLife): self
    {
        $this->drainLife = $drainLife;

        return $this;
    }

    public function getCriticalDamages(): ?int
    {
        return $this->criticalDamages;
    }

    public function setCriticalDamages(?int $criticalDamages): self
    {
        $this->criticalDamages = $criticalDamages;

        return $this;
    }

    public function getIsShield(): ?bool
    {
        return $this->isShield;
    }

    public function setIsShield(bool $isShield): self
    {
        $this->isShield = $isShield;

        return $this;
    }

    public function getIsResurrection(): ?bool
    {
        return $this->isResurrection;
    }

    public function setIsResurrection(bool $isResurrection): self
    {
        $this->isResurrection = $isResurrection;

        return $this;
    }

    public function getIsIgnoreShield(): ?bool
    {
        return $this->isIgnoreShield;
    }

    public function setIsIgnoreShield(bool $isIgnoreShield): self
    {
        $this->isIgnoreShield = $isIgnoreShield;

        return $this;
    }

    public function getIsCleanse(): ?bool
    {
        return $this->isCleanse;
    }

    public function setIsCleanse(bool $isCleanse): self
    {
        $this->isCleanse = $isCleanse;

        return $this;
    }

    public function getBoostAtb(): ?int
    {
        return $this->boostAtb;
    }

    public function setBoostAtb(?int $boostAtb): self
    {
        $this->boostAtb = $boostAtb;

        return $this;
    }

    public function getReducAtb(): ?int
    {
        return $this->reducAtb;
    }

    public function setReducAtb(?int $reducAtb): self
    {
        $this->reducAtb = $reducAtb;

        return $this;
    }

    public function getHealSP(): ?int
    {
        return $this->healSP;
    }

    public function setHealSP(?int $healSP): self
    {
        $this->healSP = $healSP;

        return $this;
    }

    public function getHitSP(): ?int
    {
        return $this->hitSP;
    }

    public function setHitSP(?int $hitSP): self
    {
        $this->hitSP = $hitSP;

        return $this;
    }

    public function getHealMP(): ?int
    {
        return $this->healMP;
    }

    public function setHealMP(?int $healMP): self
    {
        $this->healMP = $healMP;

        return $this;
    }

    public function getHitMP(): ?int
    {
        return $this->hitMP;
    }

    public function setHitMP(?int $hitMP): self
    {
        $this->hitMP = $hitMP;

        return $this;
    }

    public function getIsReplayAttack(): ?bool
    {
        return $this->isReplayAttack;
    }

    public function setIsReplayAttack(bool $isReplayAttack): self
    {
        $this->isReplayAttack = $isReplayAttack;

        return $this;
    }

    public function getAutoAtbBoost(): ?int
    {
        return $this->autoAtbBoost;
    }

    public function setAutoAtbBoost(?int $autoAtbBoost): self
    {
        $this->autoAtbBoost = $autoAtbBoost;

        return $this;
    }
}
