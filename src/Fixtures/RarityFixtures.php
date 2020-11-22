<?php

namespace App\Fixtures;

use App\Entity\Rarity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RarityFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $rarities = [
            [
                'name' => 'Commun',
                'color' => 'black'
            ],
            [
                'name' => 'Peu commun',
                'color' => 'green'
            ],
            [
                'name' => 'Rare',
                'color' => 'blue'
            ],
            [
                'name' => 'Héroïque',
                'color' => 'violet'
            ],
            [
                'name' => 'Épique',
                'color' => 'yellow'
            ],
            [
                'name' => 'Légendaire',
                'color' => 'orange'
            ],
            [
                'name' => 'Mythique',
                'color' => 'red'
            ],
        ];

        foreach ($rarities as $rarity) {
            $object = (new Rarity())
                ->setName($rarity['name'])
                ->setColor($rarity['color']);
            $manager->persist($object);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}