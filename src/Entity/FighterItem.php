<?php

namespace App\Entity;

use App\Repository\FighterItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FighterItemRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class FighterItem
{
    public function getFullDescription(): string
    {
        $full = $this->getItem()->getFullDescription();

        if ($this->getItem()->getMaxDurability() !== null) {
            $full .= "<p>Durabilité : " . $this->durability . " / " . $this->getItem()->getMaxDurability() . "</p>";
        }

        return $full;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDefaults(): void
    {
        if ($this->getItem()->getMaxDurability() !== null) {
            $this->setDurability($this->getItem()->getMaxDurability());
        }
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=FighterInfos::class, inversedBy="heroItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?FighterInfos $hero;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Item $item;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isEquipped;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $durability;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHero(): ?FighterInfos
    {
        return $this->hero;
    }

    public function setHero(?FighterInfos $hero): self
    {
        $this->hero = $hero;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getIsEquipped(): ?bool
    {
        return $this->isEquipped;
    }

    public function setIsEquipped(bool $isEquipped): self
    {
        $this->isEquipped = $isEquipped;

        return $this;
    }

    public function getDurability(): ?int
    {
        return $this->durability;
    }

    public function setDurability(?int $durability): self
    {
        $this->durability = $durability;

        return $this;
    }
}