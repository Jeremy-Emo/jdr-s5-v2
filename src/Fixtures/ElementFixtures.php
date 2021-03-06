<?php

namespace App\Fixtures;

use App\Entity\Element;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;

class ElementFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $elementToSave = [
            [
                'name' => 'Feu',
                'nameId' => 'fire',
                'rarity' => 100
            ],
            [
                'name' => 'Eau',
                'nameId' => 'water',
                'rarity' => 100
            ],
            [
                'name' => 'Glace',
                'nameId' => 'ice',
                'rarity' => 70
            ],
            [
                'name' => 'Terre',
                'nameId' => 'earth',
                'rarity' => 100
            ],
            [
                'name' => 'Foudre',
                'nameId' => 'thunder',
                'rarity' => 70
            ],
            [
                'name' => 'Vent',
                'nameId' => 'wind',
                'rarity' => 95
            ],
            [
                'name' => 'Physique',
                'nameId' => 'physical',
                'rarity' => 60
            ],
            [
                'name' => 'Tout',
                'nameId' => 'all',
                'rarity' => 1
            ],
            [
                'name' => 'Espace',
                'nameId' => 'space',
                'rarity' => 5
            ],
            [
                'name' => 'Temps',
                'nameId' => 'time',
                'rarity' => 5
            ],
            [
                'name' => 'Métal',
                'nameId' => 'metal',
                'rarity' => 50
            ],
            [
                'name' => 'Bois',
                'nameId' => 'wood',
                'rarity' => 50
            ],
            [
                'name' => 'Lumière',
                'nameId' => 'light',
                'rarity' => 25
            ],
            [
                'name' => 'Ténèbres',
                'nameId' => 'dark',
                'rarity' => 25
            ],
            [
                'name' => 'Poison',
                'nameId' => 'poison',
                'rarity' => 30
            ],
        ];

        $i = 1;

        foreach ($elementToSave as $element) {
            $object = (new Element())
                ->setName($element['name'])
                ->setNameId($element['nameId'])
                ->setRarity($element['rarity'])
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