<?php

namespace App\Entity;

use App\Repository\CustomEffectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomEffectRepository::class)
 */
class CustomEffect
{
    public function __toString(): string
    {
        if (!empty($this->value)) {
            return $this->name . ' (' . $this->value . ')';
        }
        return $this->name;
    }

    public function hasLinkedObjects(): bool
    {
        return ($this->items->count() > 0 || $this->skillsWithThis->count() > 0);
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
     * @ORM\OneToMany(targetEntity=FightingSkillInfo::class, mappedBy="customEffects")
     */
    private Collection $skillsWithThis;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="customEffect")
     */
    private Collection $items;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $value;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $advancedDescription;

    public function __construct()
    {
        $this->skillsWithThis = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): self
    {
        $this->value = $value;

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
