<?php

namespace App\Entity;

use App\Repository\FightingSkillInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FightingSkillInfoRepository::class)
 */
class FightingSkillInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\OneToOne(targetEntity=Skill::class, inversedBy="fightingSkillInfo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Skill $skill;

    /**
     * @ORM\OneToMany(targetEntity=ElementMultiplier::class, mappedBy="skill", orphanRemoval=true)
     */
    private Collection $elementsMultipliers;

    /**
     * @ORM\ManyToMany(targetEntity=Element::class, inversedBy="fightingSkills")
     */
    private $element;

    /**
     * @ORM\ManyToOne(targetEntity=Stat::class, inversedBy="fightingSkills")
     */
    private $statForDamage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $damageOutputMultiplier;

    public function __construct()
    {
        $this->elementsMultipliers = new ArrayCollection();
        $this->element = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * @return Collection|ElementMultiplier[]
     */
    public function getElementsMultipliers(): Collection
    {
        return $this->elementsMultipliers;
    }

    public function addElementsMultipliers(ElementMultiplier $elementMultiplier): self
    {
        if (!$this->elementsMultipliers->contains($elementMultiplier)) {
            $this->elementsMultipliers[] = $elementMultiplier;
            $elementMultiplier->setSkill($this);
        }

        return $this;
    }

    public function removeElementsMultipliers(ElementMultiplier $elementMultiplier): self
    {
        if ($this->elementsMultipliers->removeElement($elementMultiplier)) {
            // set the owning side to null (unless already changed)
            if ($elementMultiplier->getSkill() === $this) {
                $elementMultiplier->setSkill(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Element[]
     */
    public function getElement(): Collection
    {
        return $this->element;
    }

    public function addElement(Element $element): self
    {
        if (!$this->element->contains($element)) {
            $this->element[] = $element;
        }

        return $this;
    }

    public function removeElement(Element $element): self
    {
        $this->element->removeElement($element);

        return $this;
    }

    public function getStatForDamage(): ?Stat
    {
        return $this->statForDamage;
    }

    public function setStatForDamage(?Stat $statForDamage): self
    {
        $this->statForDamage = $statForDamage;

        return $this;
    }

    public function getDamageOutputMultiplier(): ?int
    {
        return $this->damageOutputMultiplier;
    }

    public function setDamageOutputMultiplier(?int $damageOutputMultiplier): self
    {
        $this->damageOutputMultiplier = $damageOutputMultiplier;

        return $this;
    }
}
