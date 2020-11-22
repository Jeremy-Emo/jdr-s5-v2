<?php

namespace App\Fixtures;

use App\Entity\ItemSlot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ItemSlotFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $slots = [
            [
                'name' => 'Mains',
                'nameId' => 'hands'
            ],
            [
                'name' => 'Jambes',
                'nameId' => 'legs'
            ],
            [
                'name' => 'Pieds',
                'nameId' => 'boots'
            ],
            [
                'name' => 'Torse',
                'nameId' => 'body'
            ],
            [
                'name' => 'Tête',
                'nameId' => 'head'
            ],
            [
                'name' => 'Cou',
                'nameId' => 'jewelry'
            ],
            [
                'name' => 'Anneau inférieur',
                'nameId' => 'inferiorRing'
            ],
            [
                'name' => 'Anneau supérieur',
                'nameId' => 'superiorRing'
            ],
        ];

        foreach ($slots as $slot) {
            $object = (new ItemSlot())
                ->setName($slot['name'])
                ->setNameId($slot['nameId']);
            $manager->persist($object);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}