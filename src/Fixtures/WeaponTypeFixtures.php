<?php

namespace App\Fixtures;

use App\Entity\WeaponType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;

class WeaponTypeFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $weapons = [
            [
                'name' => 'Épée 1 main',
                'nameId' => 'swordOneHanded',
                'hands' => 1,
            ],
            [
                'name' => 'Hache 1 main',
                'nameId' => 'axeOneHanded',
                'hands' => 1,
            ],
            [
                'name' => 'Épée 2 mains',
                'nameId' => 'swordTwoHanded',
                'hands' => 2,
            ],
            [
                'name' => 'Hache 2 mains',
                'nameId' => 'axeTwoHanded',
                'hands' => 2,
            ],
            [
                'name' => 'Bouclier 1 main',
                'nameId' => 'shieldOneHanded',
                'hands' => 1,
            ],
            [
                'name' => 'Bouclier 2 mains',
                'nameId' => 'shieldTwoHanded',
                'hands' => 2,
            ],
            [
                'name' => 'Arc',
                'nameId' => 'bow',
                'hands' => 2,
            ],
            [
                'name' => 'Bâton inférieur',
                'nameId' => 'staffOneHanded',
                'hands' => 1,
            ],
            [
                'name' => 'Bâton supérieur',
                'nameId' => 'staffTwoHanded',
                'hands' => 2,
            ],
            [
                'name' => 'Lance',
                'nameId' => 'spear',
                'hands' => 2,
            ],
            [
                'name' => 'Arme à feu',
                'nameId' => 'gun',
                'hands' => 1,
            ],
            [
                'name' => 'Dague',
                'nameId' => 'dagger',
                'hands' => 1,
            ],
            [
                'name' => 'Marteau 2 mains',
                'nameId' => 'hammerTwoHanded',
                'hands' => 2,
            ],
            [
                'name' => 'Faux 2 mains',
                'nameId' => 'scytheTwoHanded',
                'hands' => 2,
            ],
        ];

        $i = 1;

        foreach ($weapons as $weapon) {
            $object = (new WeaponType())
                ->setName($weapon['name'])
                ->setNameId($weapon['nameId'])
                ->setHandNumber($weapon['hands'])
                ->setId($i)
            ;
            $manager->persist($object);

            $metadata = $manager->getClassMetaData(get_class($object));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());
            $i ++;
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}