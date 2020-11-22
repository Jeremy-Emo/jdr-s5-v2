<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item extends UploadImageEntity
{
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
     * @ORM\Column(type="string", length=255)
     */
    private ?string $typeId;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isConsumable;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity=CustomEffect::class)
     */
    private ?CustomEffect $customEffect;

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

    public function getTypeId(): ?string
    {
        return $this->typeId;
    }

    public function setTypeId(string $typeId): self
    {
        $this->typeId = $typeId;

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
}
