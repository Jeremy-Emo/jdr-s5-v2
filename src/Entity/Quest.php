<?php

namespace App\Entity;

use App\Repository\QuestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Quest
{
    /**
     * @ORM\PrePersist
     */
    public function setDefaults(): void
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
    }

    /**
     * @return string
     */
    public function getAttribution(): string
    {
        if ($this->getHero() !== null) {
            return "Héros : " . $this->hero->getName();
        }
        if ($this->getParty() !== null) {
            return "Groupe : " . $this->party->getName();
        }
        return "(vide)";
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
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isCompleted = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isFailed = false;

    /**
     * @ORM\ManyToOne(targetEntity=CompletionRank::class)
     */
    private ?CompletionRank $completionRank;

    /**
     * @ORM\ManyToOne(targetEntity=Hero::class, inversedBy="quests")
     */
    private ?Hero $hero;

    /**
     * @ORM\ManyToOne(targetEntity=Party::class, inversedBy="quests")
     */
    private ?Party $party;

    /**
     * @ORM\OneToMany(targetEntity=Reward::class, mappedBy="quest", orphanRemoval=true, cascade={"all"})
     */
    private Collection $rewards;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    public function __construct()
    {
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
     * @return Hero|null
     */
    public function getHero(): ?Hero
    {
        return $this->hero;
    }

    public function setHero(?Hero $hero): self
    {
        $this->hero = $hero;

        return $this;
    }

    /**
     * @return null|Party
     */
    public function getParty(): ?Party
    {
        return $this->party;
    }

    public function setParty(?Party $party): self
    {
        $this->party = $party;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
