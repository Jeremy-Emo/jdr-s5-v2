<?php

namespace App\Entity;

use App\Repository\RewardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RewardRepository::class)
 */
class Reward
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=CompletionRank::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?CompletionRank $completionRank;

    /**
     * @ORM\ManyToOne(targetEntity=Quest::class, inversedBy="rewards")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Quest $quest;

    /**
     * @ORM\ManyToOne(targetEntity=Rarity::class)
     */
    private ?Rarity $randomItem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $statPoints = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $skillPoints = 0;

    /**
     * @ORM\ManyToMany(targetEntity=Item::class)
     */
    private Collection $items;

    /**
     * @ORM\ManyToMany(targetEntity=Skill::class)
     */
    private Collection $skills;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->skills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuest(): ?Quest
    {
        return $this->quest;
    }

    public function setQuest(?Quest $quest): self
    {
        $this->quest = $quest;

        return $this;
    }

    public function getRandomItem(): ?Rarity
    {
        return $this->randomItem;
    }

    public function setRandomItem(?Rarity $randomItem): self
    {
        $this->randomItem = $randomItem;

        return $this;
    }

    public function getStatPoints(): ?int
    {
        return $this->statPoints;
    }

    public function setStatPoints(?int $statPoints): self
    {
        $this->statPoints = $statPoints;

        return $this;
    }

    public function getSkillPoints(): ?int
    {
        return $this->skillPoints;
    }

    public function setSkillPoints(?int $skillPoints): self
    {
        $this->skillPoints = $skillPoints;

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
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        $this->items->removeElement($item);

        return $this;
    }

    /**
     * @return Collection|Skill[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skills->removeElement($skill);

        return $this;
    }
}
