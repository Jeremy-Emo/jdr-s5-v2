<?php

namespace App\Manager;

use App\Entity\FighterInfos;
use App\Entity\FighterItem;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Iterable_;

class StuffManager
{
    public static function returnStuffForDisplay(FighterInfos $fighter): array
    {
        $return = [
            'weapons' => [],
            'gears' => []
        ];

        foreach ($fighter->getHeroItems() as $item) {
            if ($item->getIsEquipped() && $item->getItem()->getItemSlot() !== null) {
                $return['gears'][] = $item;
            } elseif ($item->getIsEquipped() && $item->getItem()->getBattleItemInfo()->getWeaponType() !== null) {
                $return['weapons'][] = $item;
            }
        }

        return $return;
    }

    public static function getStuffWithEmptySlots(FighterInfos $fighter, array $slots): array
    {
        $stuff = self::returnStuffForDisplay($fighter);
        $return = [
            'weapons' => $stuff['weapons'],
            'gears' => []
        ];

        foreach ($slots as $slot) {
            $toAdd = null;
            /** @var FighterItem $gear */
            foreach ($stuff['gears'] as $gear) {
                if ($gear->getItem()->getItemSlot() === $slot) {
                    $toAdd = $gear;
                }
            }
            $return['gears'][$slot->getName()] = $toAdd;
        }

        return $return;
    }
}