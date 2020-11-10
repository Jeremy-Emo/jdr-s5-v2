<?php

namespace App\Manager;

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

    public const CAP_CRITICAL_RATE = 60;
    public const ONE_PERCENT_CRITICAL_RATE = 2;

    public const CAP_RESISTANCE = 60;
    public const ONE_PERCENT_RESISTANCE = 2;
    public const MAXIMUM_RESISTANCE = 95;

    public const ONE_HP_STAMINA = 5;
    public const ONE_MP_WISDOM = 5;

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

}