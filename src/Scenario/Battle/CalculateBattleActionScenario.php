<?php

namespace App\Scenario\Battle;

use App\Entity\Battle;
use App\Entity\BattleTurn;
use App\Entity\CustomEffect;
use App\Entity\FighterItem;
use App\Exception\ScenarioException;
use App\Manager\StatManager;
use App\Repository\FighterInfosRepository;
use App\Repository\FighterSkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CalculateBattleActionScenario extends AbstractBattleScenario
{
    /** @required  */
    public CreateTurnScenario $createTurnScenario;

    /** @required  */
    public FighterInfosRepository $fighterRepository;

    /** @required  */
    public FighterSkillRepository $fighterSkillRepository;


    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    /**
     * @param Battle $battle
     * @param string $targetId
     * @param string $action
     * @return BattleTurn
     * @throws ScenarioException
     */
    public function handle(Battle $battle, string $targetId, string $action): BattleTurn
    {
        /** @var BattleTurn $activeTurn */
        $activeTurn = $battle->getTurns()->last();
        $actor = $activeTurn->getBattleState()['nextActor'];
        $fighters = $activeTurn->getBattleState()['fighters'];

        $this->actor = $this->fighterRepository->find($actor['id']);
        $this->target = $this->fighterRepository->find($targetId);
        if ($this->actor === null || $this->target === null) {
            throw new ScenarioException("Actor not found");
        }

        if ($action !== "") {
            $this->actionString .= $this->actor->getName();
            if ($this->checkStatus($actor, 'stun')) {
                $this->actionString .= " est étourdi !";
            } elseif ($this->checkStatus($actor, 'frozen')) {
                $this->actionString .= " est gelé !";
            } else {
                $fighters = $this->processBattle($fighters, $actor, $action);
                if ($this->isCasting) {
                    $this->actionString .= " prépare le sort " . $this->fSkill->getSkill()->getName() . " pour les prochains tours !";
                }
            }
        } else {
            $this->actionString .= $this->actor->getName() . " ne fait rien.";
        }

        $this->resetActorAtb($fighters, $this->actor->getId());

        return $this->createTurnScenario->handle($fighters, $activeTurn->getTurnNumber(), $this->actionString, $actor);
    }

    /**
     * @param array $fighters
     * @param array $actor
     * @param string $action
     * @return array
     * @throws ScenarioException
     * @throws \Exception
     */
    private function processBattle(array $fighters, array &$actor, string $action): array
    {
        $this->getCustomEffectsOnActor();
        $actor = $this->calculateOffensivePower($action, $actor);
        if (!$this->isCasting) {
            //Get targets in fighters array for direct modification and get additional information like shield
            $targets = [];
            foreach ($fighters as &$fighter) {
                if (
                    ($fighter['id'] === $this->target->getId() && !$this->isAoE)
                    || ($fighter['ennemy'] === ($this->target->getMonster() !== null) && $this->isAoE)
                    || (!$fighter['ennemy'] === ($this->target->getMonster() !== null) && $this->isAoE)
                ) {
                    $targets[] = &$fighter;
                }
                unset($fighter);
            }

            $totalDamages = 0;
            foreach ($targets as &$target) {
                if (!$this->isAoE) {
                    $this->currentTarget = $this->target;
                } else {
                    $this->currentTarget = $this->fighterRepository->find($target['id']);
                }
                if ($actor['id'] === $target['id']) {
                    $target = &$actor;
                }
                $this->getCustomEffectsOnTarget();

                //Cleanse
                if ($this->fSkill->getSkill()->getFightingSkillInfo()->getIsCleanse()) {
                    $target['statuses'] = [];
                }

                if (!$this->isHeal && !$this->isShield) {
                    //Check Pression
                    $actor['currentSP'] = $actor['currentSP'] + $this->getValueOfPassiveCustomEffect($this->currentTarget, 'pression');

                    //Check esquive
                    $this->checkIfDodged();
                    if (!$this->itsADodge) {
                        $this->calculateDefensivePower();
                        $this->calculateDamages($actor, $target);

                        $this->checkShield($target);

                        $target['currentHP'] -= $this->currentDamages;

                        if (!$this->checkSurvivalSkills($target)) {
                            if ($target['currentHP'] <= 0) {
                                $target['currentHP'] = 0;
                                $target['currentShieldValue'] = 0;
                            }
                            $totalDamages += $this->currentDamages;
                        }
                    } else {
                        $this->addStringAtEnd .= $target["name"] . " a esquivé. ";
                    }
                } else {
                    $this->checkIfDodged(false);
                    if (!$this->itsADodge) {
                        if ($this->isHeal && !$this->checkStatus($target, 'anti_heal')) {
                            if ($target['currentHP'] > 0 || $this->isRez) {
                                $target['currentHP'] += $this->offensivePower;
                                if ($target['currentHP'] > $target['maxHP']) {
                                    $target['currentHP'] = $target['maxHP'];
                                }
                            }
                        }
                        if ($this->isShield) {
                            $target['currentShieldValue'] += $this->offensivePower;
                        }
                    }
                }

                //Vérifications customEffect de l'attaquant
                /** @var CustomEffect $ce */
                foreach ($this->customEffects as $ce) {
                    $this->applyCustomEffectsOnTarget($target, $ce);
                }

                if (
                    $this->fSkill !== null
                    && $this->fSkill->getSkill()->getFightingSkillInfo() !== null
                ) {
                    //Application réactions élémentaires
                    $this->checkElementalReactions($target);

                    //Application statuts
                    $resStat = StatManager::returnTotalStat('resistance', $this->currentTarget);
                    $targetRes = StatManager::calculateResistance($resStat['value']);
                    foreach ($this->fSkill->getSkill()->getFightingSkillInfo()->getBattleStates() as $state) {
                        foreach ($state->getStates() as $status) {
                            if ($targetRes < rand(1, 100) || $status->getIsTransformation() || $status->getIsBuff()) {
                                $target['statuses'][$status->getNameId()] = $state->getTurnsNumber();
                            }
                        }
                    }
                    //Drain de vie
                    if (
                        !empty($this->fSkill->getSkill()->getFightingSkillInfo()->getDrainLife())
                        && !$this->checkStatus($actor, 'anti_heal')
                    ) {
                        $actor['currentHP'] += floor($this->currentDamages * $this->fSkill->getSkill()->getFightingSkillInfo()->getDrainLife() / 100);
                        if ($actor['currentHP'] > $actor['maxHP']) {
                            $actor['currentHP'] = $actor['maxHP'];
                        }
                    }
                }
                unset($target);
            }

            //MAJ Action
            if ($this->isHeal) {
                $this->actionString .= " " . $this->offensivePower . " de soin";
            } elseif ($this->isShield) {
                $this->actionString .= " " . $this->offensivePower . " de protection";
            } else {
                $displayDamages = floor($totalDamages / count($targets));
                $this->actionString .= " " . $displayDamages . " de dégâts";
            }
            if ($this->isAoE) {
                $this->actionString .= " en zone !";
            } else {
                $this->actionString .= " sur " . $this->target->getName() . ". ";
            }
            $this->actionString .= $this->addStringAtEnd;
        }

        // Mise à jour stats après coûts du sort
        if ($this->fSkill !== null) {
            $actor['currentHP'] = $actor['currentHP'] - (int)$this->fSkill->getSkill()->getHpCost();
            if ($this->reducMPCost > 100) {
                $this->reducMPCost = 100;
            }
            $actor['currentMP'] = $actor['currentMP'] - floor((int)$this->fSkill->getSkill()->getMpCost() * (100 - $this->reducMPCost) / 100);
            $actor['currentSP'] = $actor['currentSP'] + (int)$this->fSkill->getSkill()->getSpCost();
        }

        //MAJ actor for several reasons
        foreach ($fighters as &$fighter) {
            if ($fighter['id'] === $actor['id']) {
                $fighter = $actor;
            }
            unset($fighter);
        }

        return $fighters;
    }

    /**
     * @param array $actor
     * @param array $target
     * @throws \Exception
     */
    private function calculateDamages(array $actor, array $target): void
    {
        //Check if critical
        $critical = StatManager::returnTotalStat(StatManager::CRITICAL_RATE, $this->actor);
        $cr = StatManager::calculateCriticalRate($critical['value']);
        if (
            $this->fSkill !== null
            && $this->fSkill->getSkill()->getFightingSkillInfo() !== null
            && $this->fSkill->getSkill()->getFightingSkillInfo()->getIsCriticalRateUpgraded()
        ) {
            $cr += 30;
        }
        $criticalHit = StatManager::calculateCriticalRate($cr) >= rand(0, 100);

        //Check statuses
        if ($this->checkStatus($actor, 'buff_atk')) {
            $this->offensivePower *= 1.5;
        }
        if ($this->checkStatus($actor, 'break_atk')) {
            $this->offensivePower *= 0.5;
        }
        if ($this->checkStatus($target, 'buff_def')) {
            $this->defensivePower *= 1.5;
        }
        if ($this->checkStatus($target, 'break_def')) {
            $this->defensivePower *= 0.5;
        }

        //Calculate damages
        if (
            $this->fSkill !== null
            && $this->fSkill->getSkill()->getFightingSkillInfo() !== null
            && $this->fSkill->getSkill()->getFightingSkillInfo()->getIsIgnoreDefense()
        ) {
            $this->defensivePower *= 0.1;
        }
        $this->currentDamages = $this->offensivePower - ($this->defensivePower * 0.8);

        //Apply critical
        if ($criticalHit) {
            $this->addStringAtEnd .= "Coup critique sur " . $target['name'] . " ! ";
            $criticalDamages = self::CRITICAL_BONUS;
            if (
                $this->fSkill !== null
                && $this->fSkill->getSkill()->getFightingSkillInfo() !== null
                && !empty($this->fSkill->getSkill()->getFightingSkillInfo()->getCriticalDamages())
            ) {
                $criticalDamages += $this->fSkill->getSkill()->getFightingSkillInfo()->getCriticalDamages();
            }
            $this->currentDamages *= (1 + ($criticalDamages / 100));
        }

        if ($this->currentDamages < 0) {
            $this->currentDamages = 0;
        }

        // Check counter_attack
        if (
            $this->fSkill !== null
            && $this->fSkill->getSkill()->getFightingSkillInfo() !== null
            && $this->fSkill->getSkill()->getFightingSkillInfo()->getCustomEffects() !== null
            && $this->fSkill->getSkill()->getFightingSkillInfo()->getCustomEffects()->getNameId() === "counter_attack"
        ) {
            $this->currentDamages += (2 * ($actor['maxHP'] - $actor['currentHP']));
        }

        if ($this->checkStatus($actor, 'immortal_king_undead')) {
            $this->currentDamages *= self::FIRST_IMMORTAL_KING_MULT;
        }
    }

    private function calculateDefensivePower(): void
    {
        $stuff = $this->currentTarget->getEquipment();
        /** @var FighterItem $fItem */
        foreach ($stuff as $fItem) {
            $this->defensivePower += $fItem->getItem()->getBattleItemInfo()->getArmor();
        }

        if ($this->fSkill !== null) {
            $resistances = 0;
            $elements = $this->fSkill->getSkill()->getFightingSkillInfo()->getElement();
            foreach ($elements as $element) {
                // Get stuff multipliers
                foreach ($stuff as $fItem) {
                    if (
                        $fItem->getItem()->getBattleItemInfo() !== null
                        && $fItem->getItem()->getBattleItemInfo()->getElementMultipliers() !== null
                    ) {
                        foreach ($fItem->getItem()->getBattleItemInfo()->getElementMultipliers() as $em) {
                            if ($this->checkElement($element, $em, true)) {
                                $resistances += $em->getValue();
                            }
                        }
                    }
                }

                // Get passives multipliers
                foreach ($this->currentTarget->getSkills() as $fighterSkill) {
                    if (
                        $fighterSkill->getSkill()->getIsPassive()
                        && $fighterSkill->getSkill()->getFightingSkillInfo() !== null
                        && $fighterSkill->getSkill()->getFightingSkillInfo()->getElementsMultipliers() !== null
                    ) {
                        foreach ($fighterSkill->getSkill()->getFightingSkillInfo()->getElementsMultipliers() as $em) {
                            if ($this->checkElement($element, $em, true)) {
                                $resistances += ($em->getValue() * $fighterSkill->getLevel());
                            }
                        }
                    }
                }
            }

            $this->defensivePower = ceil($this->defensivePower * (100 + $resistances) / 100);
        }
    }

    /**
     * @param $action
     * @param $actor
     * @return array
     * @throws ScenarioException
     * @throws \Exception
     */
    private function calculateOffensivePower($action, array $actor): array
    {
        if ($action === ContinueBattleScenario::ATTACK_WITH_WEAPON) {
            $this->actionString .= " attaque avec son arme pour ";

            // Add natural offensive ability
            $stat = StatManager::returnMetaStat(StatManager::LABEL_OP, $this->actor);
            $this->offensivePower += $stat['value'];

            // Add weapons offensive abilities
            foreach ($this->actor->getHeroItems() as $actorItem) {
                if (
                    $actorItem->getIsEquipped()
                    && $actorItem->getItem()->getBattleItemInfo() !== null
                    && $actorItem->getItem()->getBattleItemInfo()->getWeaponType() !== null
                ) {
                    $this->offensivePower += $actorItem->getItem()->getBattleItemInfo()->getTrueDamages();
                }
            }

            //END
        } else {
            $this->fSkill = $this->fighterSkillRepository->find($action);
            if ($this->fSkill === null) {
                throw new ScenarioException("Skill not found");
            }

            //Get skill usage
            if ($this->fSkill->getSkill()->getFightingSkillInfo() !== null) {
                if ($this->fSkill->getSkill()->getFightingSkillInfo()->getCastingTime()) {
                    if (isset($actor['spellUsed']) && $actor['spellUsed'] === $this->fSkill->getSkill()->getName()) {
                        $actor['spellUsed'] = null;
                    } else {
                        $reduction = $this->getValueOfPassiveCustomEffect($this->actor, 'speed_casting');
                        $actor['isCasting'] = $this->fSkill->getSkill()->getFightingSkillInfo()->getCastingTime() - $reduction;
                        if ($actor['isCasting'] < 0) {
                            $actor['isCasting'] = 0;
                        }
                        if ($actor['isCasting'] > 0) {
                            $actor['spellUsed'] = $this->fSkill->getSkill()->getName();
                            $this->isCasting = true;
                        }
                    }
                }
                $this->isHeal = $this->fSkill->getSkill()->getFightingSkillInfo()->getIsHeal();
                $this->isRez = $this->fSkill->getSkill()->getFightingSkillInfo()->getIsResurrection();
                $this->isAoE = $this->fSkill->getSkill()->getFightingSkillInfo()->getIsAoE();
                $this->isShield = $this->fSkill->getSkill()->getFightingSkillInfo()->getIsShield();
                $this->isIgnoreShield = $this->fSkill->getSkill()->getFightingSkillInfo()->getIsIgnoreShield();
                /** @var CustomEffect $ce */
                foreach ($this->customEffects as $ce) {
                    if ($ce->getNameId() === 'mana_reduction') {
                        $this->reducMPCost += $ce->getValue();
                    }
                    if ($ce->getNameId() === 'speed_casting') {
                        $this->speedCasting += 1;
                    }
                }
            }

            if (!$this->isCasting) {
                $this->actionString .= " lance " . $this->fSkill->getSkill()->getName() . " pour ";

                $multipliers = 0;
                $elements = $this->fSkill->getSkill()->getFightingSkillInfo()->getElement();
                foreach ($elements as $element) {
                    // Get stuff multipliers
                    foreach ($this->actor->getHeroItems() as $actorItem) {
                        if (
                            $actorItem->getIsEquipped() && $actorItem->getItem()->getBattleItemInfo() !== null
                            && $actorItem->getItem()->getBattleItemInfo()->getElementMultipliers() !== null
                        ) {
                            foreach ($actorItem->getItem()->getBattleItemInfo()->getElementMultipliers() as $em) {
                                if ($this->checkElement($element, $em)) {
                                    $multipliers += $em->getValue();
                                }
                            }
                        }
                    }

                    // Get passives multipliers
                    foreach ($this->actor->getSkills() as $fighterSkill) {
                        if (
                            $fighterSkill->getSkill()->getIsPassive()
                            && $fighterSkill->getSkill()->getFightingSkillInfo() !== null
                            && $fighterSkill->getSkill()->getFightingSkillInfo()->getElementsMultipliers() !== null
                        ) {
                            foreach ($fighterSkill->getSkill()->getFightingSkillInfo()->getElementsMultipliers() as $em) {
                                if ($this->checkElement($element, $em)) {
                                    $multipliers += ($em->getValue() * $fighterSkill->getLevel());
                                }
                            }
                        }
                    }
                }

                // Calculate stats multipliers
                $totalStats = StatManager::returnTotalStats($this->actor);
                $baseOffensivePower = 0;
                foreach ($totalStats as $stat) {
                    foreach ($this->fSkill->getSkill()->getFightingSkillInfo()->getStatMultipliers() as $skillStat) {
                        if ($stat['id'] === $skillStat->getStat()->getId()) {
                            $baseOffensivePower += floor($skillStat->getValue() * $this->fSkill->getLevel() * $stat['value'] / 100);
                        }
                    }
                }

                foreach ($elements as $element) {
                    if ($this->actor->getAffinity() === $element) {
                        $this->triggerAffinity = 100;
                    }
                }

                //Calculate total
                $this->offensivePower += floor($baseOffensivePower * (100 + $multipliers + $this->triggerAffinity) / 100);
            }

            //END
        }

        if ($this->checkStatus($actor, 'immortal_king_barbarian')) {
            $this->offensivePower = $this->offensivePower * self::SECOND_IMMORTAL_KING_MULT;
        }

        return $actor;
    }
}