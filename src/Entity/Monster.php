<?php

namespace App\Entity;

use App\Repository\MonsterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MonsterRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Monster
{
    public function __toString(): string
    {
        return $this->name;
    }

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
     * @ORM\OneToOne(targetEntity=FighterInfos::class, mappedBy="monster", cascade={"persist", "remove"})
     */
    private ?FighterInfos $fighterInfos;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isFinished = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Element::class, inversedBy="monsters")
     */
    private ?Element $elementAffinity;

    /**
     * @ORM\ManyToMany(targetEntity=Battle::class, mappedBy="monsters")
     */
    private Collection $battles;

    public function __construct()
    {
        $this->battles = new ArrayCollection();
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

    public function getFighterInfos(): ?FighterInfos
    {
        return $this->fighterInfos;
    }

    public function setFighterInfos(?FighterInfos $fighterInfos): self
    {
        $this->fighterInfos = $fighterInfos;

        // set (or unset) the owning side of the relation if necessary
        $newMonster = null === $fighterInfos ? null : $this;
        if ($fighterInfos->getMonster() !== $newMonster) {
            $fighterInfos->setMonster($newMonster);
        }

        return $this;
    }

    public function getIsFinished(): ?bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): self
    {
        $this->isFinished = $isFinished;

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

    public function getElementAffinity(): ?Element
    {
        return $this->elementAffinity;
    }

    public function setElementAffinity(?Element $elementAffinity): self
    {
        $this->elementAffinity = $elementAffinity;

        return $this;
    }

    /**
     * @return Collection|Battle[]
     */
    public function getBattles(): Collection
    {
        return $this->battles;
    }

    public function addBattle(Battle $battle): self
    {
        if (!$this->battles->contains($battle)) {
            $this->battles[] = $battle;
            $battle->addMonster($this);
        }

        return $this;
    }

    public function removeBattle(Battle $battle): self
    {
        if ($this->battles->removeElement($battle)) {
            $battle->removeMonster($this);
        }

        return $this;
    }
}
