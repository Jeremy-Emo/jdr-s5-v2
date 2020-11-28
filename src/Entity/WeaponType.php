<?php

namespace App\Entity;

use App\Repository\WeaponTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeaponTypeRepository::class)
 */
class WeaponType
{
    public function __toString(): string
    {
        return $this->name;
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
     * @ORM\OneToMany(targetEntity=FightingSkillInfo::class, mappedBy="needWeaponType")
     */
    private Collection $fightingSkillInfos;

    /**
     * @ORM\OneToMany(targetEntity=BattleItemInfo::class, mappedBy="weaponType")
     */
    private Collection $items;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $handNumber;

    public function __construct()
    {
        $this->fightingSkillInfos = new ArrayCollection();
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
    public function getFightingSkillInfos(): Collection
    {
        return $this->fightingSkillInfos;
    }

    public function addFightingSkillInfo(FightingSkillInfo $fightingSkillInfo): self
    {
        if (!$this->fightingSkillInfos->contains($fightingSkillInfo)) {
            $this->fightingSkillInfos[] = $fightingSkillInfo;
            $fightingSkillInfo->setNeedWeaponType($this);
        }

        return $this;
    }

    public function removeFightingSkillInfo(FightingSkillInfo $fightingSkillInfo): self
    {
        if ($this->fightingSkillInfos->removeElement($fightingSkillInfo)) {
            // set the owning side to null (unless already changed)
            if ($fightingSkillInfo->getNeedWeaponType() === $this) {
                $fightingSkillInfo->setNeedWeaponType(null);
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

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setWeaponType($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getWeaponType() === $this) {
                $item->setWeaponType(null);
            }
        }

        return $this;
    }

    public function getHandNumber(): ?int
    {
        return $this->handNumber;
    }

    public function setHandNumber(int $handNumber): self
    {
        $this->handNumber = $handNumber;

        return $this;
    }
}
