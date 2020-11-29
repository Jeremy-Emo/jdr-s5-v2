<?php

namespace App\Entity;

use App\Repository\HeroRepository;
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
    private $party;

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
}
