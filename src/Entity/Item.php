<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item extends UploadImageEntity
{
    public function __toString(): string
    {
        return $this->getName();
    }

    public function getFullDescription(): string
    {
        $full = "<p>Rareté : <span class='" . $this->rarity->getColor() . "'>" . $this->rarity->getName() . "</span></p>";

        if (!empty($this->description)) {
            $full .= "<p class='pa-10-bottom border-bottom'>" . $this->description . "</p>";
        }

        if (!empty($this->getItemSlot())) {
            $full .= "<p>Équipement : " . $this->getItemSlot()->getName() . "</p>";
        }

        if ($this->getBattleItemInfo() !== null) {
            $full .= $this->getBattleItemInfo()->getFullDescription();
        }

        if (!empty($this->customEffect)) {
            $full .= "<p>Effet spécial : " . $this->customEffect . "</p>";
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
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Rarity::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Rarity $rarity;

    /**
     * @ORM\ManyToOne(targetEntity=ItemSlot::class, inversedBy="items")
     */
    private ?ItemSlot $itemSlot;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isConsumable;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity=CustomEffect::class, inversedBy="items")
     */
    private ?CustomEffect $customEffect;

    /**
     * @ORM\OneToOne(targetEntity=BattleItemInfo::class, mappedBy="item", cascade={"persist", "remove"})
     */
    private ?BattleItemInfo $battleItemInfo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $maxDurability;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRarity(): ?Rarity
    {
        return $this->rarity;
    }

    public function setRarity(?Rarity $rarity): self
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getItemSlot(): ?ItemSlot
    {
        return $this->itemSlot;
    }

    public function setItemSlot(?ItemSlot $itemSlot): self
    {
        $this->itemSlot = $itemSlot;

        return $this;
    }

    public function getIsConsumable(): ?bool
    {
        return $this->isConsumable;
    }

    public function setIsConsumable(bool $isConsumable): self
    {
        $this->isConsumable = $isConsumable;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCustomEffect(): ?CustomEffect
    {
        return $this->customEffect;
    }

    public function setCustomEffect(?CustomEffect $customEffect): self
    {
        $this->customEffect = $customEffect;

        return $this;
    }

    public function getBattleItemInfo(): ?BattleItemInfo
    {
        return $this->battleItemInfo;
    }

    public function setBattleItemInfo(BattleItemInfo $battleItemInfo): self
    {
        $this->battleItemInfo = $battleItemInfo;

        // set the owning side of the relation if necessary
        if ($battleItemInfo->getItem() !== $this) {
            $battleItemInfo->setItem($this);
        }

        return $this;
    }

    public function getMaxDurability(): ?int
    {
        return $this->maxDurability;
    }

    public function setMaxDurability(?int $maxDurability): self
    {
        $this->maxDurability = $maxDurability;

        return $this;
    }
}
