<?php

namespace App\Entity;

use App\Repository\StatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatRepository::class)
 */
class Stat
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
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $nameId;

    /**
     * @ORM\OneToMany(targetEntity=FightingSkillInfo::class, mappedBy="statForDamage")
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $fightingSkill->setStatForDamage($this);
        }

        return $this;
    }

    public function removeFightingSkill(FightingSkillInfo $fightingSkill): self
    {
        if ($this->fightingSkills->removeElement($fightingSkill)) {
            // set the owning side to null (unless already changed)
            if ($fightingSkill->getStatForDamage() === $this) {
                $fightingSkill->setStatForDamage(null);
            }
        }

        return $this;
    }
}
