<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Account implements UserInterface
{
    /**
     * @return Party|null
     */
    public function getCurrentParty(): ?Party
    {
        foreach ($this->getParties() as $party) {
            if ($party->getIsActive()) {
                return $party;
            }
        }
        return null;
    }

    /**
     * @return Hero|null
     */
    public function getCurrentHero(): ?Hero
    {
        foreach ($this->getHeroes() as $hero) {
            if ($hero->getIsCurrent()) {
                return $hero;
            }
        }
        return null;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDefaults(): void
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
            $this->isPasswordChangeNeeded = true;
        }
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isAdmin = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isPasswordChangeNeeded;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $lastVisitAt;

    /**
     * @ORM\OneToMany(targetEntity=Hero::class, mappedBy="account", orphanRemoval=true)
     */
    private Collection $heroes;

    /**
     * @ORM\OneToMany(targetEntity=AccountSkills::class, mappedBy="account", orphanRemoval=true)
     */
    private Collection $accountSkills;

    /**
     * @ORM\OneToMany(targetEntity=Party::class, mappedBy="mj", orphanRemoval=true)
     */
    private Collection $parties;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isMJ = false;

    public function __construct()
    {
        $this->heroes = new ArrayCollection();
        $this->accountSkills = new ArrayCollection();
        $this->parties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        if ($this->isAdmin) {
            $roles[] = 'ROLE_ADMIN';
        }

        if ($this->isMJ) {
            $roles[] = 'ROLE_MJ';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getIsPasswordChangeNeeded(): ?bool
    {
        return $this->isPasswordChangeNeeded;
    }

    public function setIsPasswordChangeNeeded(bool $isPasswordChangeNeeded): self
    {
        $this->isPasswordChangeNeeded = $isPasswordChangeNeeded;

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

    public function getLastVisitAt(): ?\DateTimeInterface
    {
        return $this->lastVisitAt;
    }

    public function setLastVisitAt(?\DateTimeInterface $lastVisitAt): self
    {
        $this->lastVisitAt = $lastVisitAt;

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
            $hero->setAccount($this);
        }

        return $this;
    }

    public function removeHero(Hero $hero): self
    {
        if ($this->heroes->removeElement($hero)) {
            // set the owning side to null (unless already changed)
            if ($hero->getAccount() === $this) {
                $hero->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AccountSkills[]
     */
    public function getAccountSkills(): Collection
    {
        return $this->accountSkills;
    }

    public function addAccountSkill(AccountSkills $accountSkill): self
    {
        if (!$this->accountSkills->contains($accountSkill)) {
            $this->accountSkills[] = $accountSkill;
            $accountSkill->setAccount($this);
        }

        return $this;
    }

    public function removeAccountSkill(AccountSkills $accountSkill): self
    {
        if ($this->accountSkills->removeElement($accountSkill)) {
            // set the owning side to null (unless already changed)
            if ($accountSkill->getAccount() === $this) {
                $accountSkill->setAccount(null);
            }
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
            $party->setMj($this);
        }

        return $this;
    }

    public function removeParty(Party $party): self
    {
        if ($this->parties->removeElement($party)) {
            // set the owning side to null (unless already changed)
            if ($party->getMj() === $this) {
                $party->setMj(null);
            }
        }

        return $this;
    }

    public function getIsMJ(): ?bool
    {
        return $this->isMJ;
    }

    public function setIsMJ(bool $isMJ): self
    {
        $this->isMJ = $isMJ;

        return $this;
    }
}
