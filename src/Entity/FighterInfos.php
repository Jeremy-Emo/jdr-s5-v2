<?php

namespace App\Entity;

use App\Repository\FighterInfosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FighterInfosRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class FighterInfos
{
    /**
     * @ORM\PrePersist
     */
    public function setDefaults(): void
    {
        if (empty($this->statPoints)) {
            $this->statPoints = 0;
        }
        if (empty($this->skillPoints)) {
            $this->skillPoints = 0;
        }
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\OneToOne(targetEntity=Hero::class, inversedBy="fighterInfos", cascade={"persist", "remove"})
     */
    private ?Hero $hero;

    /**
     * @ORM\OneToOne(targetEntity=Monster::class, inversedBy="fighterInfos", cascade={"persist", "remove"})
     */
    private ?Monster $monster;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $statPoints;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $skillPoints;

    /**
     * @ORM\OneToMany(targetEntity=FighterStat::class, mappedBy="fighter", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $stats;

    /**
     * @ORM\OneToMany(targetEntity=FighterSkill::class, mappedBy="fighter", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private Collection $skills;

    public function __construct()
    {
        $this->stats = new ArrayCollection();
        $this->skills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHero(): ?Hero
    {
        return $this->hero;
    }

    public function setHero(?Hero $hero): self
    {
        $this->hero = $hero;

        return $this;
    }

    public function getMonster(): ?Monster
    {
        return $this->monster;
    }

    public function setMonster(?Monster $monster): self
    {
        $this->monster = $monster;

        return $this;
    }

    public function getStatPoints(): ?int
    {
        return $this->statPoints;
    }

    public function setStatPoints(int $statPoints): self
    {
        $this->statPoints = $statPoints;

        return $this;
    }

    public function getSkillPoints(): ?int
    {
        return $this->skillPoints;
    }

    public function setSkillPoints(int $skillPoints): self
    {
        $this->skillPoints = $skillPoints;

        return $this;
    }

    /**
     * @return Collection|FighterStat[]
     */
    public function getStats(): Collection
    {
        return $this->stats;
    }

    public function addStat(FighterStat $stat): self
    {
        if (!$this->stats->contains($stat)) {
            $this->stats[] = $stat;
            $stat->setFighter($this);
        }

        return $this;
    }

    public function removeStat(FighterStat $stat): self
    {
        if ($this->stats->removeElement($stat)) {
            // set the owning side to null (unless already changed)
            if ($stat->getFighter() === $this) {
                $stat->setFighter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FighterSkill[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(FighterSkill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
            $skill->setFighter($this);
        }

        return $this;
    }

    public function removeSkill(FighterSkill $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getFighter() === $this) {
                $skill->setFighter(null);
            }
        }

        return $this;
    }
}
