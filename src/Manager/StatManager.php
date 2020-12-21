<?php

namespace App\Manager;

use App\Entity\FighterInfos;
use App\Entity\FighterStat;
use App\Entity\Stat;

class StatManager
{
    public const STRENGTH = 'force';
    public const STAMINA = 'vitalite';
    public const INTELLIGENCE = 'intelligence';
    public const WISDOM = 'sagesse';
    public const AGILITY = 'agilite';
    public const CRITICAL_RATE = 'taux critique';
    public const PERCEPTION = 'perception';
    public const CHARISMA = 'charisme';
    public const RESISTANCE = 'resistance';
    public const FURTIVE = 'furtivite';
    public const LEADERSHIP = 'leadership';

    public const LABEL_HP = 'Points de vie';
    public const LABEL_MP = 'Points de mana';
    public const LABEL_SP = 'Fatigue';
    public const LABEL_DODGE = 'Esquive';
    public const LABEL_SPEED = 'Vitesse';
    public const LABEL_OP = 'Capacité offensive naturelle';

    public const CAP_CRITICAL_RATE = 60;
    public const ONE_PERCENT_CRITICAL_RATE = 2;

    public const CAP_RESISTANCE = 60;
    public const ONE_PERCENT_RESISTANCE = 2;
    public const MAXIMUM_RESISTANCE = 95;

    public const ONE_HP_STAMINA = 5;
    public const ONE_MP_WISDOM = 5;
    public const ONE_SP_STRENGTH = 2;
    public const ONE_SPEED_AGILITY = 0.5;
    public const ONE_DODGE_AGILITY = 0.1;
    public const ONE_OFFENSIVE_POWER_STRENGTH = 0.05;

    /**
     * @param int $cr
     * @return int
     */
    public static function calculateCriticalRate(int $cr): int
    {
        $capCriticalRateScore = self::CAP_CRITICAL_RATE * self::ONE_PERCENT_CRITICAL_RATE;
        if ($cr > $capCriticalRateScore) {
            $percent = self::CAP_CRITICAL_RATE;
            $percent += (($cr - $capCriticalRateScore) / (self::ONE_PERCENT_CRITICAL_RATE * 4));
        } else {
            $percent = $cr / self::ONE_PERCENT_CRITICAL_RATE;
        }
        return floor($percent);
    }

    /**
     * @param int $resistance
     * @return int
     */
    public static function calculateResistance(int $resistance): int
    {
        $capResistanceScore = self::CAP_RESISTANCE * self::ONE_PERCENT_RESISTANCE;
        if ($resistance > $capResistanceScore) {
            $percent = self::CAP_RESISTANCE;
            $percent += (($resistance - $capResistanceScore) / (self::ONE_PERCENT_RESISTANCE * 4));
        } else {
            $percent = $resistance / self::ONE_PERCENT_RESISTANCE;
        }
        return (floor($percent) < self::MAXIMUM_RESISTANCE) ? floor($percent) : self::MAXIMUM_RESISTANCE;
    }

    /**
     * @param int $vitality
     * @return int
     */
    public static function calculateMaxHP(int $vitality): int
    {
        return $vitality * self::ONE_HP_STAMINA;
    }

    /**
     * @param int $wisdom
     * @return int
     */
    public static function calculateMaxMP(int $wisdom): int
    {
        return $wisdom * self::ONE_MP_WISDOM;
    }

    public static function calculateSpeed(int $agility): int
    {
        return ceil($agility * self::ONE_SPEED_AGILITY);
    }

    public static function calculateDodge(int $agility): int
    {
        return ceil($agility * self::ONE_DODGE_AGILITY);
    }

    /**
     * @param int $strength
     * @return int
     */
    public static function calculateMaxSP(int $strength): int
    {
        return $strength * self::ONE_SP_STRENGTH;
    }

    /**
     * @param int $strength
     * @return int
     */
    public static function calculateOffensivePower(int $strength): int
    {
        return $strength * self::ONE_OFFENSIVE_POWER_STRENGTH;
    }

    /**
     * @param string $name
     * @param FighterInfos $fighter
     * @return array
     * @throws \Exception
     */
    public static function returnMetaStat(string $name, FighterInfos $fighter): array
    {
        $stats = self::returnMetaStats($fighter);
        foreach ($stats as $stat) {
            if ($stat['name'] === $name) {
                return $stat;
            }
        }
        throw new \Exception("Stat not found");
    }

    public static function returnMetaStats(FighterInfos $fighter): array
    {
        $statsToReturn = [];

        foreach ($fighter->getStats() as $stat) {
            switch ($stat->getStat()->getNameId()) {
                case self::STAMINA:
                    $statsToReturn[] = [
                        'name' => self::LABEL_HP,
                        'value' => $fighter->getCurrentHP() . " / " . self::calculateMaxHP(
                            ceil($stat->getValue() * self::getBonus($fighter, $stat) / 100)
                        )
                    ];
                    break;
                case self::STRENGTH:
                    $statsToReturn[] = [
                        'name' => self::LABEL_SP,
                        'value' => $fighter->getCurrentSP() . " / " . self::calculateMaxSP(
                            ceil($stat->getValue() * self::getBonus($fighter, $stat) / 100)
                        )
                    ];
                    $statsToReturn[] = [
                        'name' => self::LABEL_OP,
                        'value' => self::calculateOffensivePower(
                                ceil($stat->getValue() * self::getBonus($fighter, $stat) / 100)
                            )
                    ];
                    break;
                case self::WISDOM:
                    $statsToReturn[] = [
                        'name' => self::LABEL_MP,
                        'value' => $fighter->getCurrentMP() . " / " . self::calculateMaxMP(
                            ceil($stat->getValue() * self::getBonus($fighter, $stat) / 100)
                        )
                    ];
                    break;
                case self::AGILITY:
                    $statsToReturn[] = [
                        'name' => self::LABEL_SPEED,
                        'value' => self::calculateSpeed(
                            ceil($stat->getValue() * self::getBonus($fighter, $stat) / 100)
                        )
                    ];
                    $statsToReturn[] = [
                        'name' => self::LABEL_DODGE,
                        'value' => self::calculateDodge(
                            ceil($stat->getValue() * self::getBonus($fighter, $stat) / 100)
                        )
                    ];
                    break;
                default:
                    break;
            }
        }

        return $statsToReturn;
    }

    /**
     * @param string $nameId
     * @param FighterInfos $fighter
     * @return array
     * @throws \Exception
     */
    public static function returnTotalStat(string $nameId, FighterInfos $fighter): array
    {
        $stats = self::returnTotalStats($fighter);
        foreach ($stats as $stat) {
            if ($stat['nameId'] === $nameId) {
                return $stat;
            }
        }
        throw new \Exception("Stat not found");
    }

    /**
     * @param FighterInfos $fighter
     * @return array
     */
    public static function returnTotalStats(FighterInfos $fighter): array
    {
        $statsToReturn = [];
        foreach ($fighter->getStats() as $stat) {
            $statsToReturn[] = [
                'name' => $stat->getStat()->getName(),
                'nameId' => $stat->getStat()->getNameId(),
                'description' => $stat->getStat()->getDescription(),
                'value' => ceil($stat->getValue() * self::getBonus($fighter, $stat) / 100),
                'id' => $stat->getStat()->getId()
            ];
        }
        return $statsToReturn;
    }

    /**
     * @param FighterInfos $fighter
     * @param FighterStat $stat
     * @return int
     */
    private static function getBonus(FighterInfos $fighter, FighterStat $stat): int
    {
        $stat = $stat->getStat();
        $bonusToStat = 100;
        foreach ($fighter->getSkills() as $fighterSkill) {
            $bonuses = $fighterSkill->getSkill()->getStatBonusPercents();
            foreach ($bonuses as $bonus) {
                if ($bonus->getStat()->getId() === $stat->getId()) {
                    $bonusToStat += ($bonus->getValue() * $fighterSkill->getLevel());
                }
            }
        }
        foreach ($fighter->getHeroItems() as $item) {
            if ($item->getIsEquipped()) {
                $bonuses = $item->getItem()->getBattleItemInfo()->getStatBonusPercents();
                foreach ($bonuses as $bonus) {
                    if ($bonus->getStat()->getId() === $stat->getId()) {
                        $bonusToStat += $bonus->getValue();
                    }
                }
            }
        }
        return $bonusToStat;
    }
}