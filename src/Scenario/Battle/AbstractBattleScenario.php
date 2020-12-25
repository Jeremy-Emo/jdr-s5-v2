<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\CustomEffect;
use App\Entity\Element;
use App\Entity\ElementMultiplier;
use App\Entity\FighterInfos;
use App\Entity\FighterItem;
use App\Entity\FighterSkill;
use App\Manager\StatManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

abstract class AbstractBattleScenario extends AbstractScenario
{
    protected string $actionString = "";
    protected string $addStringAtEnd = "";
    protected ?FighterInfos $actor;
    protected ?FighterInfos $target;
    protected ?FighterInfos $currentTarget;

    protected ?bool $isHeal = false;
    protected ?bool $isRez = false;
    protected ?bool $isShield = false;
    protected ?bool $isAoE = false;
    protected ?bool $isIgnoreShield = false;
    protected int $offensivePower = 0;
    protected int $defensivePower = 0;
    protected ?FighterSkill $fSkill = null;
    protected int $currentDamages = 0;
    protected bool $itsADodge = false;
    protected int $triggerAffinity = 0;
    protected int $reducMPCost = 0;
    protected int $speedCasting = 0;
    protected array $customEffects = [];
    protected array $targetCustomEffects = [];
    protected bool $isCasting = false;

    public const UNIVERSAL_ELEMENT = 'all';
    public const CRITICAL_BONUS = 50;

    //BUFFS BONUSES
    public const FIRST_IMMORTAL_KING_MULT = 0.5;
    public const SECOND_IMMORTAL_KING_MULT = 1.5;
    public const VALKYRIE_ADD_DAMAGES = 30;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    protected function applyCustomEffectsOnTarget(array &$target, CustomEffect $ce): void
    {
        //TODO : implement here special custom effects on target
    }

    /**
     * @param $fighter
     */
    protected function checkElementalReactions(&$fighter): void
    {
        if ($this->fSkill === null || $this->fSkill->getSkill()->getFightingSkillInfo() === null) {
            return;
        }

        foreach ($this->fSkill->getSkill()->getFightingSkillInfo()->getElement() as $element) {
            // Check waterized + ice
            if ($element->getNameId() === 'ice' && $this->checkStatus($fighter, 'waterized')) {
                $fighter['statuses'][] = [
                    'frozen' => 1,
                    'slow' => 2,
                ];
            }
            // Check waterized + thunder
            if ($element->getNameId() === 'thunder' && $this->checkStatus($fighter, 'waterized')) {
                $fighter['currentHP'] = $fighter['currentHP'] - floor($fighter['maxHP'] * 0.15);
                if ($fighter['currentHP'] < 0) {
                    $fighter['currentHP'] = 0;
                }
            }
            // Check ignite + fire
            if ($element->getNameId() === 'fire' && $this->checkStatus($fighter, 'ignite')) {
                $fighter['statuses'][] = [
                    'ignite' => 5,
                    'anti_heal' => 1,
                ];
            }
            // Check thunder + thunder
            if ($element->getNameId() === 'thunder' && $this->checkStatus($fighter, 'thunder')) {
                $fighter['statuses'][] = [
                    'stun' => 1,
                ];
            }
            // Check thunder + ice
            if ($element->getNameId() === 'ice' && $this->checkStatus($fighter, 'thunder')) {
                $fighter['statuses'][] = [
                    'break_def' => 1,
                ];
                $fighter['currentMP'] = $fighter['currentMP'] - floor($fighter['maxMP'] * 0.10);
                if ($fighter['currentMP'] < 0) {
                    $fighter['currentMP'] = 0;
                }
            }
        }
    }

    protected function getCustomEffectsOnTarget(): void
    {
        $stuff = $this->currentTarget->getEquipment();
        /** @var FighterItem $item */
        foreach ($stuff as $item) {
            if ($item->getItem()->getCustomEffect() !== null) {
                $this->targetCustomEffects[] = $item->getItem()->getCustomEffect();
            }
        }
        $passives = $this->currentTarget->getSkills();
        foreach ($passives as $skill) {
            if (
                $skill->getSkill()->getIsPassive()
                && $skill->getSkill()->getFightingSkillInfo() !== null
                && $skill->getSkill()->getFightingSkillInfo()->getCustomEffects() !== null
            ) {
                $this->targetCustomEffects[] = $skill->getSkill()->getFightingSkillInfo()->getCustomEffects();
            }
        }
    }

    protected function getCustomEffectsOnActor(): void
    {
        if (
            $this->fSkill !== null
            && $this->fSkill->getSkill()->getFightingSkillInfo() !== null
            && $this->fSkill->getSkill()->getFightingSkillInfo()->getCustomEffects() !== null
        ) {
            $this->customEffects[] = $this->fSkill->getSkill()->getFightingSkillInfo()->getCustomEffects();
        }
        $stuff = $this->actor->getEquipment();
        /** @var FighterItem $item */
        foreach ($stuff as $item) {
            if ($item->getItem()->getCustomEffect() !== null) {
                $this->customEffects[] = $item->getItem()->getCustomEffect();
            }
        }
        $passives = $this->actor->getSkills();
        foreach ($passives as $skill) {
            if (
                $skill->getSkill()->getIsPassive()
                && $skill->getSkill()->getFightingSkillInfo() !== null
                && $skill->getSkill()->getFightingSkillInfo()->getCustomEffects() !== null
            ) {
                $this->customEffects[] = $skill->getSkill()->getFightingSkillInfo()->getCustomEffects();
            }
        }
    }

    /**
     * @param bool $checkDodgeStat
     * @throws \Exception
     */
    protected function checkIfDodged(bool $checkDodgeStat = true): void
    {
        if ($checkDodgeStat) {
            $dodgeStat = StatManager::returnMetaStat(StatManager::LABEL_DODGE, $this->currentTarget);
            $rand = rand(0, 2000);
            if ($dodgeStat['value'] >= $rand) {
                $this->itsADodge = true;
            }
        }

        if (
            !$this->itsADodge
            && $this->fSkill !== null
            && $this->fSkill->getSkill()->getFightingSkillInfo() !== null
            && !empty($this->fSkill->getSkill()->getFightingSkillInfo()->getAccuracy())
        ) {
            $this->itsADodge = rand(0, 99) < $this->fSkill->getSkill()->getFightingSkillInfo()->getAccuracy();
        }
    }

    protected function getValueOfPassiveCustomEffect(FighterInfos $fighter, string $nameId): int
    {
        $value = 0;
        foreach ($fighter->getSkills() as $fSkill) {
            if (
                $fSkill->getSkill()->getIsPassive()
                && $fSkill->getSkill()->getFightingSkillInfo() !== null
            ) {
                $customEffect = $fSkill->getSkill()->getFightingSkillInfo()->getCustomEffects();
                if ($customEffect !== null && $customEffect->getNameId() === $nameId) {
                    $value += ($customEffect->getValue() ?? 1);
                }
            }
        }

        /** @var FighterItem $eItem */
        foreach ($fighter->getEquipment() as $eItem) {
            if (
                $eItem->getItem()->getCustomEffect() !== null
                && $eItem->getItem()->getCustomEffect()->getNameId() === $nameId
            ) {
                $value += ($eItem->getItem()->getCustomEffect()->getValue() ?? 1);
            }
        }

        return $value;
    }

    /**
     * @param array $fighter
     * @param string $statusName
     * @return bool
     */
    protected function checkStatus(array $fighter, string $statusName): bool
    {
        return (
            isset($fighter['statuses'])
            && !empty($fighter['statuses'][$statusName])
        );
    }

    /**
     * @param Element $element
     * @param ElementMultiplier $em
     * @param bool $isResistance
     * @return bool
     */
    protected function checkElement(Element $element, ElementMultiplier $em, $isResistance = false): bool
    {
        return (
            $em->getElement() === $element
            || $element->getNameId() === self::UNIVERSAL_ELEMENT
            || $em->getElement()->getNameId() === self::UNIVERSAL_ELEMENT
        ) && $em->getIsResistance() === $isResistance;
    }

    /**
     * @param array $fighters
     * @param int $actorId
     */
    protected function resetActorAtb(array &$fighters, int $actorId): void
    {
        foreach ($fighters as &$fighter) {
            if ((int)$fighter['id'] === $actorId) {
                $fighter['atb'] = 0;
            }
            if (!empty($fighter['changeAtb'])) {
                $fighter['atb'] += $fighter['changeAtb'];
                $fighter['changeAtb'] = 0;
            }
            unset($fighter);
        }
    }

    protected function killActor(array &$fighters, int $actorId): void
    {
        foreach ($fighters as &$fighter) {
            if ((int)$fighter['id'] === $actorId) {
                $fighter['currentHP'] = 0;
            }
            unset($fighter);
        }
    }

    /**
     * @param array $target
     */
    protected function checkShield(array &$target): void
    {
        if ($target['currentShieldValue'] > 0 && !$this->isIgnoreShield) {
            if ($target['currentShieldValue'] < $this->currentDamages) {
                $this->currentDamages -= $target['currentShieldValue'];
                $target['currentShieldValue'] = 0;
            } else {
                $target['currentShieldValue'] -= $this->currentDamages;
                $this->currentDamages = 0;
            }
        }
    }

    /**
     * @param array $target
     * @return bool
     */
    protected function checkSurvivalSkills(array &$target): bool
    {
        $hasSurvived = false;

        if ($this->checkStatus($target, 'survive')) {
            foreach ($target['statuses'] as $key => &$status) {
                if ($key === 'survive') {
                    $status = 0;
                    break;
                }
            }
            $target['currentHP'] += $this->currentDamages;
            $hasSurvived = true;
        } else {
            //Custom effect good_luck
            foreach ($this->targetCustomEffects as $ce) {
                if ($ce->getNameId() === "good_luck") {
                    if (rand(1, 100) === 7) {
                        $hasSurvived = true;
                    }
                }
            }

            if (!$hasSurvived) {
                //Custom effect survive
                $canIgnoreDeath = 0;
                /** @var CustomEffect $ce */
                foreach ($this->targetCustomEffects as $ce) {
                    if ($ce->getNameId() === "survive") {
                        if (!isset($target['surviveDeathCounter'])) {
                            $target['surviveDeathCounter'] = 0;
                        }
                        $canIgnoreDeath ++;
                    }
                }
                if (isset($target['surviveDeathCounter']) && $target['surviveDeathCounter'] < $canIgnoreDeath ) {
                    $target['surviveDeathCounter'] += 1;
                    $target['currentHP'] += $this->currentDamages;
                    $hasSurvived = true;
                }
            }
        }

        return $hasSurvived;
    }
}