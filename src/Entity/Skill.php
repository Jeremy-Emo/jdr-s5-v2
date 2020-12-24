<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SkillRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Skill
{
    public function __toString(): string
    {
        return $this->name;
    }

    public function getTagsForSearch(): string
    {
        $tags = "|";
        foreach ($this->getTags() as $tag) {
            $tags .= $tag->getId() . "|";
        }
        return $tags;
    }

    public function getFullDescription(?int $level = 1, ?bool $needFullDescription = true): string
    {
        if ($level === 0) {
            $level = 1;
        }

        $full = "<p>" . $this->description .  "</p>";

        if ($this->isPassive) {
            $full .= "<p class='bold'>Compétence passive</p>";
        }

        $notUsableInBattle = "";
        if ($this->isUsableInBattle) {
            $full .= $this->getFightingSkillInfo()->generateDescription($level, $needFullDescription);
        } else {
            $notUsableInBattle .= "<p class='red'>Non utilisable en combat</p>";
        }
        if ((int) $this->mpCost > 0) {
            $full .= "<p>Coût en mana : " . $this->mpCost . "</p>";
        }
        if ((int) $this->hpCost > 0) {
            $full .= "<p>Coût en PV : " . $this->hpCost . "</p>";
        }
        if ((int) $this->spCost > 0) {
            $full .= "<p>Coût en fatigue : " . $this->spCost . "</p>";
        }

        foreach ($this->getStatBonusPercents() as $bonusPercent) {
            $full .= "<p>" . $bonusPercent->getStat()->getName() . " +" . ($bonusPercent->getValue() * $level) . "%</p>";
        }

        $full .= $notUsableInBattle;

        if ($this->needSkill !== null && $this->neededSkillLevel > 0 && $needFullDescription) {
            $full .= "<p class='red'>Nécessite la compétence " . $this->needSkill . " au niveau " . $this->neededSkillLevel . "</p>";
        }

        return $full;
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
     * @ORM\Column(type="integer")
     */
    private ?int $cost = 1;

    /**
     * @ORM\OneToOne(targetEntity=FightingSkillInfo::class, mappedBy="skill", cascade={"persist", "remove"})
     */
    private ?FightingSkillInfo $fightingSkillInfo;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isPassive = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isUsableInBattle = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $mpCost = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $hpCost = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $spCost = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Skill::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ?Skill $needSkill;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $neededSkillLevel;

    /**
     * @ORM\OneToMany(targetEntity=AccountSkills::class, mappedBy="skill", orphanRemoval=true)
     */
    private Collection $accountSkills;

    /**
     * @ORM\ManyToMany(targetEntity=SkillTag::class, inversedBy="skills")
     */
    private Collection $tags;

    /**
     * @ORM\ManyToMany(targetEntity=StatBonusPercent::class, inversedBy="skill", cascade={"all"})
     */
    private Collection $statBonusPercents;

    public function __construct()
    {
        $this->accountSkills = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->statBonusPercents = new ArrayCollection();
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

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getFightingSkillInfo(): ?FightingSkillInfo
    {
        return $this->fightingSkillInfo;
    }

    public function setFightingSkillInfo(FightingSkillInfo $fightingSkillInfo): self
    {
        $this->fightingSkillInfo = $fightingSkillInfo;

        // set the owning side of the relation if necessary
        if ($fightingSkillInfo->getSkill() !== $this) {
            $fightingSkillInfo->setSkill($this);
        }

        return $this;
    }

    public function getIsPassive(): ?bool
    {
        return $this->isPassive;
    }

    public function setIsPassive(bool $isPassive): self
    {
        $this->isPassive = $isPassive;

        return $this;
    }

    public function getIsUsableInBattle(): ?bool
    {
        return $this->isUsableInBattle;
    }

    public function setIsUsableInBattle(bool $isUsableInBattle): self
    {
        $this->isUsableInBattle = $isUsableInBattle;

        return $this;
    }

    public function getMpCost(): ?int
    {
        return $this->mpCost;
    }

    public function setMpCost(?int $mpCost): self
    {
        $this->mpCost = $mpCost;

        return $this;
    }

    public function getHpCost(): ?int
    {
        return $this->hpCost;
    }

    public function setHpCost(?int $hpCost): self
    {
        $this->hpCost = $hpCost;

        return $this;
    }

    public function getSpCost(): ?int
    {
        return $this->spCost;
    }

    public function setSpCost(?int $spCost): self
    {
        $this->spCost = $spCost;

        return $this;
    }

    public function getNeedSkill(): ?self
    {
        return $this->needSkill;
    }

    public function setNeedSkill(?self $needSkill): self
    {
        $this->needSkill = $needSkill;

        return $this;
    }

    public function getNeededSkillLevel(): ?int
    {
        return $this->neededSkillLevel;
    }

    public function setNeededSkillLevel(?int $neededSkillLevel): self
    {
        $this->neededSkillLevel = $neededSkillLevel;

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
            $accountSkill->setSkill($this);
        }

        return $this;
    }

    public function removeAccountSkill(AccountSkills $accountSkill): self
    {
        if ($this->accountSkills->removeElement($accountSkill)) {
            // set the owning side to null (unless already changed)
            if ($accountSkill->getSkill() === $this) {
                $accountSkill->setSkill(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SkillTag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(SkillTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(SkillTag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|StatBonusPercent[]
     */
    public function getStatBonusPercents(): Collection
    {
        return $this->statBonusPercents;
    }

    public function addStatBonusPercent(StatBonusPercent $statBonusPercent): self
    {
        if (!$this->statBonusPercents->contains($statBonusPercent)) {
            $this->statBonusPercents[] = $statBonusPercent;
            $statBonusPercent->addSkill($this);
        }

        return $this;
    }

    public function removeStatBonusPercent(StatBonusPercent $statBonusPercent): self
    {
        if ($this->statBonusPercents->removeElement($statBonusPercent)) {
            $statBonusPercent->removeSkill($this);
        }

        return $this;
    }
}
