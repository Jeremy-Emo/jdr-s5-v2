<?php

namespace App\Entity;

use App\Repository\StatMultiplierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatMultiplierRepository::class)
 */
class StatMultiplier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Stat::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Stat $stat;

    /**
     * @ORM\ManyToOne(targetEntity=FightingSkillInfo::class, inversedBy="statMultipliers")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?FightingSkillInfo $skill;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStat(): ?Stat
    {
        return $this->stat;
    }

    public function setStat(?Stat $stat): self
    {
        $this->stat = $stat;

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

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
