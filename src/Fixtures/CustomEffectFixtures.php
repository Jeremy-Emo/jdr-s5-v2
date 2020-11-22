<?php


namespace App\Fixtures;


use App\Entity\CustomEffect;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CustomEffectFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $effects = [
            [
                'name' => 'Drain de vie',
                'nameId' => 'drain_life_percent',
                'value' => 15
            ]
        ];

        foreach ($effects as $effect) {
            $object = (new CustomEffect())
                ->setName($effect['name'])
                ->setNameId($effect['nameId'])
                ->setValue($effect['value'])
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}