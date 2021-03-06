<?php

namespace App\Entity;

use App\Repository\StatBonusPercentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatBonusPercentRepository::class)
 */
class StatBonusPercent
{
    public function __toString(): string
    {
        return $this->stat->getName() . " (" . $this->value . "%)";
    }

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
     * @ORM\Column(type="integer")
     */
    private ?int $value;

    /**
     * @ORM\ManyToMany(targetEntity=Skill::class, mappedBy="statBonusPercents")
     */
    private Collection $skill;

    /**
     * @ORM\ManyToMany(targetEntity=BattleItemInfo::class, mappedBy="statBonusPercents")
     */
    private Collection $item;

    public function __construct()
    {
        $this->skill = new ArrayCollection();
        $this->item = new ArrayCollection();
    }

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

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|Skill[]
     */
    public function getSkill(): Collection
    {
        return $this->skill;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skill->contains($skill)) {
            $this->skill[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skill->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection|BattleItemInfo[]
     */
    public function getItem(): Collection
    {
        return $this->item;
    }

    public function addItem(BattleItemInfo $item): self
    {
        if (!$this->item->contains($item)) {
            $this->item[] = $item;
        }

        return $this;
    }

    public function removeItem(BattleItemInfo $item): self
    {
        $this->item->removeElement($item);

        return $this;
    }
}
