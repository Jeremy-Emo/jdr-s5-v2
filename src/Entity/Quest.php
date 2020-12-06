<?php

namespace App\Entity;

use App\Repository\QuestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestRepository::class)
 */
class Quest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCompleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFailed;

    /**
     * @ORM\ManyToOne(targetEntity=CompletionRank::class)
     */
    private $completionRank;

    /**
     * @ORM\ManyToMany(targetEntity=Hero::class, mappedBy="quests")
     */
    private $heroes;

    /**
     * @ORM\ManyToMany(targetEntity=Party::class, mappedBy="quests")
     */
    private $parties;

    /**
     * @ORM\OneToMany(targetEntity=Reward::class, mappedBy="quest", orphanRemoval=true)
     */
    private $rewards;

    public function __construct()
    {
        $this->heroes = new ArrayCollection();
        $this->parties = new ArrayCollection();
        $this->rewards = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    public function getIsFailed(): ?bool
    {
        return $this->isFailed;
    }

    public function setIsFailed(bool $isFailed): self
    {
        $this->isFailed = $isFailed;

        return $this;
    }

    public function getCompletionRank(): ?CompletionRank
    {
        return $this->completionRank;
    }

    public function setCompletionRank(?CompletionRank $completionRank): self
    {
        $this->completionRank = $completionRank;

        return $this;
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
            $hero->addQuest($this);
        }

        return $this;
    }

    public function removeHero(Hero $hero): self
    {
        if ($this->heroes->removeElement($hero)) {
            $hero->removeQuest($this);
        }

        return $this;
    }

    /**
     * @return Collection|Party[]
     */
    public function getParties(): Collection
    {
        return $this->parties;
    }

    public function addParty(Party $party): self
    {
        if (!$this->parties->contains($party)) {
            $this->parties[] = $party;
            $party->addQuest($this);
        }

        return $this;
    }

    public function removeParty(Party $party): self
    {
        if ($this->parties->removeElement($party)) {
            $party->removeQuest($this);
        }

        return $this;
    }

    /**
     * @return Collection|Reward[]
     */
    public function getRewards(): Collection
    {
        return $this->rewards;
    }

    public function addReward(Reward $reward): self
    {
        if (!$this->rewards->contains($reward)) {
            $this->rewards[] = $reward;
            $reward->setQuest($this);
        }

        return $this;
    }

    public function removeReward(Reward $reward): self
    {
        if ($this->rewards->removeElement($reward)) {
            // set the owning side to null (unless already changed)
            if ($reward->getQuest() === $this) {
                $reward->setQuest(null);
            }
        }

        return $this;
    }
}
