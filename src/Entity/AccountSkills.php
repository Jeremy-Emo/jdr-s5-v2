<?php

namespace App\Entity;

use App\Repository\AccountSkillsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountSkillsRepository::class)
 */
class AccountSkills
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="accountSkills")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Account $account;

    /**
     * @ORM\ManyToOne(targetEntity=Skill::class, inversedBy="accountSkills")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Skill $skill;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $level;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
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
