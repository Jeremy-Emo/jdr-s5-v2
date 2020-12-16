<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\Battle;
use App\Entity\BattleTurn;
use App\Entity\Element;
use App\Entity\ElementMultiplier;
use App\Entity\FighterInfos;
use App\Entity\FighterItem;
use App\Entity\FighterSkill;
use App\Exception\ScenarioException;
use App\Manager\StatManager;
use App\Repository\FighterInfosRepository;
use App\Repository\FighterSkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CalculateBattleActionScenario extends AbstractScenario
{
    /** @required  */
    public CreateTurnScenario $createTurnScenario;

    /** @required  */
    public FighterInfosRepository $fighterRepository;

    /** @required  */
    public FighterSkillRepository $fighterSkillRepository;

    private string $actionString = "";
    private string $addStringAtEnd = "";
    private ?FighterInfos $actor;
    private ?FighterInfos $target;
    private ?FighterInfos $currentTarget;

    private bool $isDefenseIgnored = false;
    private bool $isHeal = false;
    private bool $isAoE = false;
    private int $offensivePower = 0;
    private int $defensivePower = 0;
    private ?FighterSkill $fSkill = null;
    private int $currentDamages = 0;
    private bool $itsADodge = false;

    public const UNIVERSAL_ELEMENT = 'all';

    //BUFFS BONUSES
    public const FIRST_IMMORTAL_KING_MULT = 1;
    public const SECOND_IMMORTAL_KING_MULT = 1.5;


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
            $this->processBattle($fighters, $action);
        } else {
            $this->actionString .= $this->actor->getName() . " ne fait rien.";
        }

        $this->resetActorAtb($fighters, $this->actor->getId());

        return $this->createTurnScenario->handle($fighters, $activeTurn->getTurnNumber(), $this->actionString);
    }

    /**
     * @param array $fighters
     * @param string $action
     * @throws ScenarioException
     */
    private function processBattle(array &$fighters, string $action): void
    {
        $this->calculateOffensivePower($action, $fighters['nextActor']);

        //Get targets in fighters array for direct modification and get additional information like shield
        $targets = [];
        foreach ($fighters as &$fighter) {
            if (
                ($fighter['id'] === $this->target->getId() && !$this->isAoE)
                || ($fighter['ennemy'] === ($this->target->getMonster() !== null) && $this->isAoE)
                || (!$fighter['ennemy'] === ($this->target->getMonster() !== null) && $this->isAoE)
            ) {
                $targets[] &= $fighter;
            }
        }

        foreach ($targets as &$target) {
            if (!$this->isAoE) {
                $this->currentTarget = $this->target;
            } else {
                $this->currentTarget = $this->fighterRepository->find($target['id']);
            }

            $this->checkIfDodged();
            if (!$this->itsADodge) {
                $this->calculateDefensivePower();

                //TODO : calculate damages (think about statuses) (think about criticals)
                //TODO : apply damages (think about shields)

                //TODO : apply statuses
            } else {
                $this->addStringAtEnd .= $target["name"] . " a esquivé. ";
            }
        }

        if ($this->isHeal) {
            $this->actionString .= " de soin";
        } else {
            $this->actionString .= " de dégâts";
        }

        if ($this->isAoE) {
            $this->actionString .= " en zone !";
        } else {
            $this->actionString .= " sur " . $this->target->getName() . ". ";
        }
        $this->actionString .= $this->addStringAtEnd;
    }

    private function checkIfDodged(): void
    {
        $dodgeStat = StatManager::returnMetaStat(StatManager::LABEL_DODGE, $this->currentTarget);
        $rand = rand(0, 2000);
        if ($dodgeStat['value'] <= $rand) {
            $this->itsADodge = true;
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
            $this->defensivePower = ceil($this->defensivePower * $resistances / 100);
        }
    }

    /**
     * @param $action
     * @param $actor
     * @throws ScenarioException
     */
    private function calculateOffensivePower($action, array $actor): void
    {
        if ($action === ContinueBattleScenario::ATTACK_WITH_WEAPON) {
            $this->actionString .= " attaque avec son arme pour ";

            // Add natural offensive ability
            $stats = StatManager::returnMetaStats($this->actor);
            foreach ($stats as $stat) {
                if ($stat['name'] === StatManager::LABEL_OP) {
                    $this->offensivePower += $stat['value'];
                }
            }

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

            //Calculate total
            $this->offensivePower += floor($baseOffensivePower * $multipliers / 100);

            //END
        }

        if ($this->checkStatus($actor, 'immortal_king_barbarian')) {
            $this->offensivePower *= self::SECOND_IMMORTAL_KING_MULT;
        }
    }

    /**
     * @param array $fighter
     * @param string $statusName
     * @return bool
     */
    private function checkStatus(array $fighter, string $statusName): bool
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
    private function checkElement(Element $element, ElementMultiplier $em, $isResistance = false): bool
    {
        return (
            $em->getElement() === $element
            || $element->getNameId() === self::UNIVERSAL_ELEMENT
            || $em->getElement()->getNameId() === self::UNIVERSAL_ELEMENT
        ) && $em->getIsResistance() === $isResistance;
    }

    /**
     * @param array $fighters
     * @param string $actorId
     */
    private function resetActorAtb(array &$fighters, string $actorId): void
    {
        foreach ($fighters as &$fighter) {
            if ((string)$fighter['id'] === $actorId) {
                $fighter['atb'] = 0;
            }
        }
    }
}