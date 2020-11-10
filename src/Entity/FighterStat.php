<?php

namespace App\Entity;

use App\Repository\FighterStatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FighterStatRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class FighterStat
{
    /**
     * @ORM\PrePersist
     */
    public function setDefaults(): void
    {
        if (empty($this->value)) {
            $this->value = 0;
        }
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Stat::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Stat $stat;

    /**
     * @ORM\ManyToOne(targetEntity=FighterInfos::class, inversedBy="stats")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?FighterInfos $fighter;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStat(): ?Stat
    {
        return $this->stat;
    }

    public function setStat(?Stat $stat): self
    {
        $this->stat = $stat;

        return $this;
    }

    public function getFighter(): ?FighterInfos
    {
        return $this->fighter;
    }

    public function setFighter(?FighterInfos $fighter): self
    {
        $this->fighter = $fighter;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
