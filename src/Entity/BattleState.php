<?php

namespace App\Entity;

use App\Repository\BattleStateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BattleStateRepository::class)
 */
class BattleState extends UploadImageEntity
{
    public function __toString(): string
    {
        return $this->name;
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
     * @ORM\Column(type="string", length=255)
     */
    private ?string $nameId;

    /**
     * @ORM\ManyToMany(targetEntity=FightingSkillBattleState::class, mappedBy="states")
     */
    private Collection $fightingSkills;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isTransformation = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $advancedDescription;

    public function __construct()
    {
        $this->fightingSkills = new ArrayCollection();
    }

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

    public function getNameId(): ?string
    {
        return $this->nameId;
    }

    public function setNameId(string $nameId): self
    {
        $this->nameId = $nameId;

        return $this;
    }

    /**
     * @return Collection|FightingSkillBattleState[]
     */
    public function getFightingSkills(): Collection
    {
        return $this->fightingSkills;
    }

    public function addFightingSkill(FightingSkillBattleState $fightingSkill): self
    {
        if (!$this->fightingSkills->contains($fightingSkill)) {
            $this->fightingSkills[] = $fightingSkill;
            $fightingSkill->addState($this);
        }

        return $this;
    }

    public function removeFightingSkill(FightingSkillBattleState $fightingSkill): self
    {
        if ($this->fightingSkills->removeElement($fightingSkill)) {
            $fightingSkill->removeState($this);
        }

        return $this;
    }

    public function getIsTransformation(): ?bool
    {
        return $this->isTransformation;
    }

    public function setIsTransformation(bool $isTransformation): self
    {
        $this->isTransformation = $isTransformation;

        return $this;
    }

    public function getAdvancedDescription(): ?string
    {
        return $this->advancedDescription;
    }

    public function setAdvancedDescription(?string $advancedDescription): self
    {
        $this->advancedDescription = $advancedDescription;

        return $this;
    }
}
