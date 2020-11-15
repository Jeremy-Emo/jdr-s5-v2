<?php

namespace App\Entity;

use App\Repository\BattleSkillCustomEffectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BattleSkillCustomEffectRepository::class)
 */
class BattleSkillCustomEffect
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
     * @ORM\OneToMany(targetEntity=FightingSkillInfo::class, mappedBy="customEffects")
     */
    private Collection $skillsWithThis;

    public function __construct()
    {
        $this->skillsWithThis = new ArrayCollection();
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
    public function getSkillsWithThis(): Collection
    {
        return $this->skillsWithThis;
    }

    public function addSkillsWithThi(FightingSkillInfo $skillsWithThi): self
    {
        if (!$this->skillsWithThis->contains($skillsWithThi)) {
            $this->skillsWithThis[] = $skillsWithThi;
            $skillsWithThi->setCustomEffects($this);
        }

        return $this;
    }

    public function removeSkillsWithThi(FightingSkillInfo $skillsWithThi): self
    {
        if ($this->skillsWithThis->removeElement($skillsWithThi)) {
            // set the owning side to null (unless already changed)
            if ($skillsWithThi->getCustomEffects() === $this) {
                $skillsWithThi->setCustomEffects(null);
            }
        }

        return $this;
    }
}
