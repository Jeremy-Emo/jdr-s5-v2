<?php

namespace App\Entity;

use App\Repository\FighterSkillRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FighterSkillRepository::class)
 */
class FighterSkill
{
    /**
     * @ORM\PrePersist
     */
    public function setDefaults(): void
    {
        if (empty($this->level)) {
            $this->level = 1;
        }
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Skill::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Skill $skill;

    /**
     * @ORM\ManyToOne(targetEntity=FighterInfos::class, inversedBy="skills")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?FighterInfos $fighter;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $level;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getFighter(): ?FighterInfos
    {
        return $this->fighter;
    }

    public function setFighter(?FighterInfos $fighter): self
    {
        $this->fighter = $fighter;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
}
