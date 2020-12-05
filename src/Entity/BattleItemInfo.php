<?php

namespace App\Entity;

use App\Repository\BattleItemInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BattleItemInfoRepository::class)
 */
class BattleItemInfo
{
    public function getFullDescription(): string
    {
        $full = "";

        if (!empty($this->getWeaponType())) {
            $full .= "<p>Équipement : " . $this->getWeaponType()->getName() . "</p>";
        }

        if (!empty($this->armor)) {
            $full .= "<p>Défense : " . $this->armor . "</p>";
        }

        if (!empty($this->trueDamages)) {
            $full .= "<p>Capacité offensive : " . $this->trueDamages . "</p>";
        }

        if (!empty($this->drainLife)) {
            $full .= "<p>Drain de vie : " . $this->drainLife . "%</p>";
        }

        foreach ($this->getStatBonusPercents() as $bonusPercent) {
            $full .= "<p>" . $bonusPercent->getStat()->getName() . " +" . $bonusPercent->getValue() . "%</p>";
        }

        if ($this->getElementMultipliers()->count() > 0) {
            $damages = "";
            $res = "";
            foreach ($this->getElementMultipliers() as $mult) {
                if ($mult->getIsResistance()) {
                    $res .= "<li>" . $mult->getElement()->getName() . " : " . $mult->getValue() . "%</li>";
                } else {
                    $damages .= "<li>" . $mult->getElement()->getName() . " : " . $mult->getValue() . "%</li>";
                }
            }
            if ($damages !== "") {
                $full .= "<p>Multiplicateurs élémentaux :</p><ul>";
                $full .= $damages;
                $full .= "</ul>";
            }
            if ($res !== "") {
                $full .= "<p>Résistances élémentaires :</p><ul>";
                $full .= $res;
                $full .= "</ul>";
            }
        }

        return $full;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\OneToOne(targetEntity=Item::class, inversedBy="battleItemInfo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Item $item = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $trueDamages;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $armor;

    /**
     * @ORM\ManyToOne(targetEntity=WeaponType::class, inversedBy="items")
     */
    private ?WeaponType $weaponType;

    /**
     * @ORM\OneToMany(targetEntity=ElementMultiplier::class, mappedBy="item", cascade={"persist", "remove"})
     */
    private Collection $elementMultipliers;

    /**
     * @ORM\ManyToMany(targetEntity=StatBonusPercent::class, inversedBy="item", cascade={"all"})
     */
    private Collection $statBonusPercents;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $drainLife = 0;

    public function __construct()
    {
        $this->elementMultipliers = new ArrayCollection();
        $this->statBonusPercents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getTrueDamages(): ?int
    {
        return $this->trueDamages;
    }

    public function setTrueDamages(?int $trueDamages): self
    {
        $this->trueDamages = $trueDamages;

        return $this;
    }

    public function getArmor(): ?int
    {
        return $this->armor;
    }

    public function setArmor(?int $armor): self
    {
        $this->armor = $armor;

        return $this;
    }

    public function getWeaponType(): ?WeaponType
    {
        return $this->weaponType;
    }

    public function setWeaponType(?WeaponType $weaponType): self
    {
        $this->weaponType = $weaponType;

        return $this;
    }

    /**
     * @return Collection|ElementMultiplier[]
     */
    public function getElementMultipliers(): Collection
    {
        return $this->elementMultipliers;
    }

    public function addElementMultiplier(ElementMultiplier $elementMultiplier): self
    {
        if (!$this->elementMultipliers->contains($elementMultiplier)) {
            $this->elementMultipliers[] = $elementMultiplier;
            $elementMultiplier->setItem($this);
        }

        return $this;
    }

    public function removeElementMultiplier(ElementMultiplier $elementMultiplier): self
    {
        if ($this->elementMultipliers->removeElement($elementMultiplier)) {
            // set the owning side to null (unless already changed)
            if ($elementMultiplier->getItem() === $this) {
                $elementMultiplier->setItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StatBonusPercent[]
     */
    public function getStatBonusPercents(): Collection
    {
        return $this->statBonusPercents;
    }

    public function addStatBonusPercent(StatBonusPercent $statBonusPercent): self
    {
        if (!$this->statBonusPercents->contains($statBonusPercent)) {
            $this->statBonusPercents[] = $statBonusPercent;
            $statBonusPercent->addItem($this);
        }

        return $this;
    }

    public function removeStatBonusPercent(StatBonusPercent $statBonusPercent): self
    {
        if ($this->statBonusPercents->removeElement($statBonusPercent)) {
            $statBonusPercent->removeItem($this);
        }

        return $this;
    }

    public function getDrainLife(): ?int
    {
        return $this->drainLife;
    }

    public function setDrainLife(int $drainLife): self
    {
        $this->drainLife = $drainLife;

        return $this;
    }
}
