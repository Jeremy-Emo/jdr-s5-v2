<?php

namespace App\Entity;

use App\Repository\BattleItemInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BattleItemInfoRepository::class)
 */
class BattleItemInfo
{
    public function getFullDescription(): string
    {
        $full = "";

        if (!empty($this->armor)) {
            $full .= "<p>Défense : " . $this->armor . "</p>";
        }

        if (!empty($this->trueDamages)) {
            $full .= "<p>Capacité offensive : " . $this->trueDamages . "</p>";
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
}
