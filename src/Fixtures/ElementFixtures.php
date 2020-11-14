<?php

namespace App\Fixtures;

use App\Entity\Element;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ElementFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $elementToSave = [
            [
                'name' => 'Feu',
                'nameId' => 'fire'
            ],
            [
                'name' => 'Eau',
                'nameId' => 'water'
            ],
            [
                'name' => 'Glace',
                'nameId' => 'ice'
            ],
            [
                'name' => 'Terre',
                'nameId' => 'earth'
            ],
            [
                'name' => 'Foudre',
                'nameId' => 'thunder'
            ],
            [
                'name' => 'Vent',
                'nameId' => 'wind'
            ],
            [
                'name' => 'Physique',
                'nameId' => 'physical'
            ],
            [
                'name' => 'Tout',
                'nameId' => 'tout'
            ],
            [
                'name' => 'Espace',
                'nameId' => 'space'
            ],
            [
                'name' => 'Temps',
                'nameId' => 'time'
            ],
        ];

        foreach ($elementToSave as $element) {
            $object = (new Element())
                ->setName($element['name'])
                ->setNameId($element['nameId'])
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }
}