<?php

namespace App\Entity;

use App\Repository\HeroItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HeroItemRepository::class)
 */
class FighterItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=FighterInfos::class, inversedBy="heroItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hero;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEquipped;

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
}
