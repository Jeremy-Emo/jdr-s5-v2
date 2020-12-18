<?php

namespace App\Entity;

use App\Repository\PartyItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartyItemRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class PartyItem
{
    /**
     * @ORM\PrePersist
     */
    public function setDefaults(): void
    {
        if (empty($this->addedAt)) {
            $this->addedAt = new \DateTime();
        }
        if ($this->getItem()->getMaxDurability() !== null && $this->getDurability() === null) {
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
     * @ORM\ManyToOne(targetEntity=Item::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Item $item;

    /**
     * @ORM\ManyToOne(targetEntity=Party::class, inversedBy="partyItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Party $party;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $addedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $durability;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getParty(): ?Party
    {
        return $this->party;
    }

    public function setParty(?Party $party): self
    {
        $this->party = $party;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeInterface $addedAt): self
    {
        $this->addedAt = $addedAt;

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
