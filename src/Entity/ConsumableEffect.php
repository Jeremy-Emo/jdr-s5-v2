<?php

namespace App\Entity;

use App\Repository\ConsumableEffectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsumableEffectRepository::class)
 */
class ConsumableEffect
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $editHP;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $editMP;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $editSP;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $editStatPoints;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $editSkillPoints;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEditHP(): ?int
    {
        return $this->editHP;
    }

    public function setEditHP(?int $editHP): self
    {
        $this->editHP = $editHP;

        return $this;
    }

    public function getEditMP(): ?int
    {
        return $this->editMP;
    }

    public function setEditMP(?int $editMP): self
    {
        $this->editMP = $editMP;

        return $this;
    }

    public function getEditSP(): ?int
    {
        return $this->editSP;
    }

    public function setEditSP(?int $editSP): self
    {
        $this->editSP = $editSP;

        return $this;
    }

    public function getEditStatPoints(): ?int
    {
        return $this->editStatPoints;
    }

    public function setEditStatPoints(?int $editStatPoints): self
    {
        $this->editStatPoints = $editStatPoints;

        return $this;
    }

    public function getEditSkillPoints(): ?int
    {
        return $this->editSkillPoints;
    }

    public function setEditSkillPoints(?int $editSkillPoints): self
    {
        $this->editSkillPoints = $editSkillPoints;

        return $this;
    }
}
