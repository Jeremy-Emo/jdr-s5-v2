<?php

namespace App\Entity;

use App\Repository\RewardRepository;
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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CompletionRank::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $completionRank;

    /**
     * @ORM\ManyToOne(targetEntity=Quest::class, inversedBy="rewards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quest;

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
}
