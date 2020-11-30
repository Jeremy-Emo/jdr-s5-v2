<?php

namespace App\Manager;

use App\Entity\FighterInfos;

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
                $return['gears'][] = $item->getItem();
            } elseif ($item->getIsEquipped() && $item->getItem()->getBattleItemInfo()->getWeaponType() !== null) {
                $return['weapons'][] = $item->getItem();
            }
        }

        return $return;
    }
}