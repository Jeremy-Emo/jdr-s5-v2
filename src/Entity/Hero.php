<?php

namespace App\Entity;

use App\Repository\HeroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=HeroRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Hero extends UploadImageEntity
{
    public function __toString(): string
    {
        return $this->getName() . ' (' . $this->getAccount()->getUsername() . ')';
    }

    /**
     * @ORM\PrePersist
     */
    public function setDefaults(): void
    {
        if (empty($this->isCurrent)) {
            $this->isCurrent = false;
        }
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
        if (empty($this->isDead)) {
            $this->isDead = false;
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
     * @ORM\Column(type="boolean")
     */
    private ?bool $isCurrent;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="heroes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Account $account;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    /**
     * @ORM\OneToOne(targetEntity=FighterInfos::class, mappedBy="hero", cascade={"persist", "remove"})
     */
    private ?FighterInfos $fighterInfos;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isDead;

    /**
     * @ORM\ManyToOne(targetEntity=Element::class, inversedBy="heroes")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Element $elementAffinity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $age;

    /**
     * @ORM\ManyToOne(targetEntity=Party::class, inversedBy="heroes")
     */
    private ?Party $party;

    /**
     * @ORM\OneToMany(targetEntity=HeroMoney::class, mappedBy="hero", cascade={"persist", "remove"})
     */
    private Collection $heroMoney;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isMale = false;

    /**
     * @ORM\OneToMany(targetEntity=Quest::class, mappedBy="hero")
     */
    private Collection $quests;

    /**
     * @ORM\OneToMany(targetEntity=Familiar::class, mappedBy="master")
     */
    private Collection $familiars;

    public function __construct()
    {
        $this->heroMoney = new ArrayCollection();
        $this->quests = new ArrayCollection();
        $this->familiars = new ArrayCollection();
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

    public function getIsCurrent(): ?bool
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(bool $isCurrent): self
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @param Account|null|UserInterface $account
     * @return $this
     */
    public function setAccount(?Account $account): self
    {
        $this->account = $account;

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

    public function getFighterInfos(): ?FighterInfos
    {
        return $this->fighterInfos;
    }

    public function setFighterInfos(?FighterInfos $fighterInfos): self
    {
        $this->fighterInfos = $fighterInfos;

        // set (or unset) the owning side of the relation if necessary
        $newHero = null === $fighterInfos ? null : $this;
        if ($fighterInfos->getHero() !== $newHero) {
            $fighterInfos->setHero($newHero);
        }

        return $this;
    }

    public function getIsDead(): ?bool
    {
        return $this->isDead;
    }

    public function setIsDead(bool $isDead): self
    {
        $this->isDead = $isDead;

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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

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

    /**
     * @return Collection|HeroMoney[]
     */
    public function getHeroMoney(): Collection
    {
        return $this->heroMoney;
    }

    public function addHeroMoney(HeroMoney $heroMoney): self
    {
        if (!$this->heroMoney->contains($heroMoney)) {
            $this->heroMoney[] = $heroMoney;
            $heroMoney->setHero($this);
        }

        return $this;
    }

    public function removeHeroMoney(HeroMoney $heroMoney): self
    {
        if ($this->heroMoney->removeElement($heroMoney)) {
            // set the owning side to null (unless already changed)
            if ($heroMoney->getHero() === $this) {
                $heroMoney->setHero(null);
            }
        }

        return $this;
    }

    public function getIsMale(): ?bool
    {
        return $this->isMale;
    }

    public function setIsMale(bool $isMale): self
    {
        $this->isMale = $isMale;

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
     * @return Collection|Familiar[]
     */
    public function getFamiliars(): Collection
    {
        return $this->familiars;
    }

    public function addFamiliar(Familiar $familiar): self
    {
        if (!$this->familiars->contains($familiar)) {
            $this->familiars[] = $familiar;
            $familiar->setMaster($this);
        }

        return $this;
    }

    public function removeFamiliar(Familiar $familiar): self
    {
        if ($this->familiars->removeElement($familiar)) {
            // set the owning side to null (unless already changed)
            if ($familiar->getMaster() === $this) {
                $familiar->setMaster(null);
            }
        }

        return $this;
    }
}
