<?php

namespace App\Entity;

use App\Repository\ElementMultiplierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ElementMultiplierRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ElementMultiplier
{
    /**
     * @ORM\PrePersist
     */
    public function setDefaults(): void
    {
        if (empty($this->isResistance)) {
            $this->isResistance = false;
        }
        if (empty($this->value)) {
            $this->value = 1;
        }
    }


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Element::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Element $element;

    /**
     * @ORM\ManyToOne(targetEntity=FightingSkillInfo::class, inversedBy="elementsMultipliers")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?FightingSkillInfo $skill;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isResistance;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getElement(): ?Element
    {
        return $this->element;
    }

    public function setElement(?Element $element): self
    {
        $this->element = $element;

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

    public function getIsResistance(): ?bool
    {
        return $this->isResistance;
    }

    public function setIsResistance(bool $isResistance): self
    {
        $this->isResistance = $isResistance;

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
