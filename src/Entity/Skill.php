<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SkillRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Skill
{
    /**
     * @ORM\PrePersist
     */
    public function setDefaults(): void
    {
        if (empty($this->cost)) {
            $this->cost = 1;
        }
        if (empty($this->isPassive)) {
            $this->isPassive = false;
        }
        if (empty($this->isUsableInBattle)) {
            $this->isUsableInBattle = false;
        }
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getFullDescription(): string
    {
        $full = "<p>" . $this->description .  "</p>";

        if ($this->isPassive) {
            $full .= "<p class='bold'>Compétence passive</p>";
        }

        if ($this->isUsableInBattle) {
            //TODO: implement here
            $full .= "<p>TODO</p>";
        } else {
            if ((int) $this->mpCost > 0) {
                $full .= "<p>Coût en mana : " . $this->mpCost . "</p>";
            }
            if ((int) $this->hpCost > 0) {
                $full .= "<p>Coût en PV : " . $this->hpCost . "</p>";
            }
            if ((int) $this->spCost > 0) {
                $full .= "<p>Coût en fatigue : " . $this->spCost . "</p>";
            }
            $full .= "<p class='red'>Non utilisable en combat</p>";
        }

        if ($this->needSkill !== null && $this->neededSkillLevel > 0) {
            $full .= "<p class='red'>Nécessite la compétence " . $this->needSkill->name . " au niveau " . $this->neededSkillLevel . "</p>";
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
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $cost;

    /**
     * @ORM\OneToOne(targetEntity=FightingSkillInfo::class, mappedBy="skill", cascade={"persist", "remove"})
     */
    private ?FightingSkillInfo $fightingSkillInfo;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isPassive;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isUsableInBattle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $mpCost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $hpCost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $spCost;

    /**
     * @ORM\ManyToOne(targetEntity=Skill::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ?Skill $needSkill;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $neededSkillLevel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getFightingSkillInfo(): ?FightingSkillInfo
    {
        return $this->fightingSkillInfo;
    }

    public function setFightingSkillInfo(FightingSkillInfo $fightingSkillInfo): self
    {
        $this->fightingSkillInfo = $fightingSkillInfo;

        // set the owning side of the relation if necessary
        if ($fightingSkillInfo->getSkill() !== $this) {
            $fightingSkillInfo->setSkill($this);
        }

        return $this;
    }

    public function getIsPassive(): ?bool
    {
        return $this->isPassive;
    }

    public function setIsPassive(bool $isPassive): self
    {
        $this->isPassive = $isPassive;

        return $this;
    }

    public function getIsUsableInBattle(): ?bool
    {
        return $this->isUsableInBattle;
    }

    public function setIsUsableInBattle(bool $isUsableInBattle): self
    {
        $this->isUsableInBattle = $isUsableInBattle;

        return $this;
    }

    public function getMpCost(): ?int
    {
        return $this->mpCost;
    }

    public function setMpCost(?int $mpCost): self
    {
        $this->mpCost = $mpCost;

        return $this;
    }

    public function getHpCost(): ?int
    {
        return $this->hpCost;
    }

    public function setHpCost(?int $hpCost): self
    {
        $this->hpCost = $hpCost;

        return $this;
    }

    public function getSpCost(): ?int
    {
        return $this->spCost;
    }

    public function setSpCost(?int $spCost): self
    {
        $this->spCost = $spCost;

        return $this;
    }

    public function getNeedSkill(): ?self
    {
        return $this->needSkill;
    }

    public function setNeedSkill(?self $needSkill): self
    {
        $this->needSkill = $needSkill;

        return $this;
    }

    public function getNeededSkillLevel(): ?int
    {
        return $this->neededSkillLevel;
    }

    public function setNeededSkillLevel(?int $neededSkillLevel): self
    {
        $this->neededSkillLevel = $neededSkillLevel;

        return $this;
    }
}
