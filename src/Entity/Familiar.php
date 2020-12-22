<?php

namespace App\Entity;

use App\Repository\FamiliarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FamiliarRepository::class)
 */
class Familiar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Hero::class, inversedBy="familiars")
     */
    private ?Hero $master;

    /**
     * @ORM\OneToOne(targetEntity=FighterInfos::class, mappedBy="familiar", cascade={"persist", "remove"})
     */
    private ?FighterInfos $fighterInfos;

    /**
     * @ORM\ManyToOne(targetEntity=Element::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Element $elementAffinity;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $needLeaderShip;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isInvoked;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $specie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaster(): ?Hero
    {
        return $this->master;
    }

    public function setMaster(?Hero $master): self
    {
        $this->master = $master;

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
        $newFamiliar = null === $fighterInfos ? null : $this;
        if ($fighterInfos->getFamiliar() !== $newFamiliar) {
            $fighterInfos->setFamiliar($newFamiliar);
        }

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

    public function getNeedLeaderShip(): ?int
    {
        return $this->needLeaderShip;
    }

    public function setNeedLeaderShip(int $needLeaderShip): self
    {
        $this->needLeaderShip = $needLeaderShip;

        return $this;
    }

    public function getIsInvoked(): ?bool
    {
        return $this->isInvoked;
    }

    public function setIsInvoked(bool $isInvoked): self
    {
        $this->isInvoked = $isInvoked;

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

    public function getSpecie(): ?string
    {
        return $this->specie;
    }

    public function setSpecie(string $specie): self
    {
        $this->specie = $specie;

        return $this;
    }
}
