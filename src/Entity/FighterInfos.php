<?php

namespace App\Entity;

use App\Repository\FighterInfosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FighterInfosRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class FighterInfos
{
    public function getName(): string
    {
        if ($this->getMonster() !== null) {
            return $this->getMonster()->getName();
        }
        if ($this->getHero() !== null) {
            return $this->getHero()->getName();
        }

        return "Truc inconnu";
    }

    public function getAffinity(): ?Element
    {
        if ($this->getMonster() !== null) {
            return $this->getMonster()->getElementAffinity();
        }
        if ($this->getHero() !== null) {
            return $this->getHero()->getElementAffinity();
        }

        return null;
    }

    public function getEquippedWeapons(): array
    {
        $return = [];
        foreach ($this->getHeroItems() as $fItem) {
            if (
                $fItem->getIsEquipped()
                && $fItem->getItem()->getBattleItemInfo() !== null
                && $fItem->getItem()->getBattleItemInfo()->getWeaponType() !== null
            ) {
                $return[] = $fItem;
            }
        }
        return $return;
    }

    public function getEquippedSlots(): array
    {
        $return = [];
        foreach ($this->getHeroItems() as $fItem) {
            if (
                $fItem->getIsEquipped()
                && $fItem->getItem()->getItemSlot() !== null
            ) {
                $return[] = $fItem;
            }
        }
        return $return;
    }

    public function getEquipment(): array
    {
        $return = [];
        foreach ($this->getHeroItems() as $fItem) {
            if ($fItem->getIsEquipped()) {
                $return[] = $fItem;
            }
        }
        return $return;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\OneToOne(targetEntity=Hero::class, inversedBy="fighterInfos", cascade={"persist", "remove"})
     */
    private ?Hero $hero;

    /**
     * @ORM\OneToOne(targetEntity=Monster::class, inversedBy="fighterInfos", cascade={"persist", "remove"})
     */
    private ?Monster $monster;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $statPoints = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $skillPoints = 0;

    /**
     * @ORM\OneToMany(targetEntity=FighterStat::class, mappedBy="fighter", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $stats;

    /**
     * @ORM\OneToMany(targetEntity=FighterSkill::class, mappedBy="fighter", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private Collection $skills;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $currentHP;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $currentMP;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $currentSP;

    /**
     * @ORM\OneToMany(targetEntity=FighterItem::class, mappedBy="hero", cascade={"persist", "remove"})
     */
    private Collection $heroItems;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $currentShieldValue = 0;

    public function __construct()
    {
        $this->stats = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->heroItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHero(): ?Hero
    {
        return $this->hero;
    }

    public function setHero(?Hero $hero): self
    {
        $this->hero = $hero;

        return $this;
    }

    public function getMonster(): ?Monster
    {
        return $this->monster;
    }

    public function setMonster(?Monster $monster): self
    {
        $this->monster = $monster;

        return $this;
    }

    public function getStatPoints(): ?int
    {
        return $this->statPoints;
    }

    public function setStatPoints(int $statPoints): self
    {
        $this->statPoints = $statPoints;

        return $this;
    }

    public function addStatPoints(int $adding = 0): self
    {
        $this->statPoints = $this->statPoints + $adding;

        return $this;
    }

    public function getSkillPoints(): ?int
    {
        return $this->skillPoints;
    }

    public function setSkillPoints(int $skillPoints): self
    {
        $this->skillPoints = $skillPoints;

        return $this;
    }

    public function addSkillPoints(int $adding = 0): self
    {
        $this->skillPoints = $this->skillPoints + $adding;

        return $this;
    }

    /**
     * @return Collection|FighterStat[]
     */
    public function getStats(): Collection
    {
        return $this->stats;
    }

    public function addStat(FighterStat $stat): self
    {
        if (!$this->stats->contains($stat)) {
            $this->stats[] = $stat;
            $stat->setFighter($this);
        }

        return $this;
    }

    public function removeStat(FighterStat $stat): self
    {
        if ($this->stats->removeElement($stat)) {
            // set the owning side to null (unless already changed)
            if ($stat->getFighter() === $this) {
                $stat->setFighter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FighterSkill[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(FighterSkill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
            $skill->setFighter($this);
        }

        return $this;
    }

    public function removeSkill(FighterSkill $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getFighter() === $this) {
                $skill->setFighter(null);
            }
        }

        return $this;
    }

    public function getCurrentHP(): ?int
    {
        return $this->currentHP;
    }

    public function setCurrentHP(int $currentHP): self
    {
        $this->currentHP = $currentHP;

        return $this;
    }

    public function getCurrentMP(): ?int
    {
        return $this->currentMP;
    }

    public function setCurrentMP(int $currentMP): self
    {
        $this->currentMP = $currentMP;

        return $this;
    }

    public function getCurrentSP(): ?int
    {
        return $this->currentSP;
    }

    public function setCurrentSP(int $currentSP): self
    {
        $this->currentSP = $currentSP;

        return $this;
    }

    /**
     * @return Collection|FighterItem[]
     */
    public function getHeroItems(): Collection
    {
        return $this->heroItems;
    }

    public function addHeroItem(FighterItem $heroItem): self
    {
        if (!$this->heroItems->contains($heroItem)) {
            $this->heroItems[] = $heroItem;
            $heroItem->setHero($this);
        }

        return $this;
    }

    public function removeHeroItem(FighterItem $heroItem): self
    {
        if ($this->heroItems->removeElement($heroItem)) {
            // set the owning side to null (unless already changed)
            if ($heroItem->getHero() === $this) {
                $heroItem->setHero(null);
            }
        }

        return $this;
    }

    public function getCurrentShieldValue(): ?int
    {
        return $this->currentShieldValue;
    }

    public function setCurrentShieldValue(int $currentShieldValue): self
    {
        $this->currentShieldValue = $currentShieldValue;

        return $this;
    }
}
