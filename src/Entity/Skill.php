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
    private $mpCost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hpCost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $spCost;

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
}
