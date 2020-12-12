<?php

namespace App\Entity;

use App\Repository\PartyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartyRepository::class)
 */
class Party
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
     * @ORM\OneToMany(targetEntity=Hero::class, mappedBy="party")
     */
    private Collection $heroes;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="parties")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Account $mj;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isActive = false;

    /**
     * @ORM\OneToMany(targetEntity=Quest::class, mappedBy="party")
     */
    private Collection $quests;

    /**
     * @ORM\OneToMany(targetEntity=PartyItem::class, mappedBy="party", orphanRemoval=true)
     */
    private $partyItems;

    public function __construct()
    {
        $this->heroes = new ArrayCollection();
        $this->quests = new ArrayCollection();
        $this->partyItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Hero[]
     */
    public function getHeroes(): Collection
    {
        return $this->heroes;
    }

    public function addHero(Hero $hero): self
    {
        if (!$this->heroes->contains($hero)) {
            $this->heroes[] = $hero;
            $hero->setParty($this);
        }

        return $this;
    }

    public function removeHero(Hero $hero): self
    {
        if ($this->heroes->removeElement($hero)) {
            // set the owning side to null (unless already changed)
            if ($hero->getParty() === $this) {
                $hero->setParty(null);
            }
        }

        return $this;
    }

    public function getMj(): ?Account
    {
        return $this->mj;
    }

    public function setMj(?Account $mj): self
    {
        $this->mj = $mj;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Quest[]
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    public function addQuest(Quest $quest): self
    {
        if (!$this->quests->contains($quest)) {
            $this->quests[] = $quest;
        }

        return $this;
    }

    public function removeQuest(Quest $quest): self
    {
        $this->quests->removeElement($quest);

        return $this;
    }

    /**
     * @return Collection|PartyItem[]
     */
    public function getPartyItems(): Collection
    {
        return $this->partyItems;
    }

    public function addPartyItem(PartyItem $partyItem): self
    {
        if (!$this->partyItems->contains($partyItem)) {
            $this->partyItems[] = $partyItem;
            $partyItem->setParty($this);
        }

        return $this;
    }

    public function removePartyItem(PartyItem $partyItem): self
    {
        if ($this->partyItems->removeElement($partyItem)) {
            // set the owning side to null (unless already changed)
            if ($partyItem->getParty() === $this) {
                $partyItem->setParty(null);
            }
        }

        return $this;
    }
}
