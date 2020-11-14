<?php

namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ElementRepository::class)
 */
class Element
{
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
     * @ORM\ManyToMany(targetEntity=FightingSkillInfo::class, mappedBy="element")
     */
    private $fightingSkills;

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
     * @return Collection|FightingSkillInfo[]
     */
    public function getFightingSkills(): Collection
    {
        return $this->fightingSkills;
    }

    public function addFightingSkill(FightingSkillInfo $fightingSkill): self
    {
        if (!$this->fightingSkills->contains($fightingSkill)) {
            $this->fightingSkills[] = $fightingSkill;
            $fightingSkill->addElement($this);
        }

        return $this;
    }

    public function removeFightingSkill(FightingSkillInfo $fightingSkill): self
    {
        if ($this->fightingSkills->removeElement($fightingSkill)) {
            $fightingSkill->removeElement($this);
        }

        return $this;
    }
}
