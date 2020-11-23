<?php

namespace App\Fixtures;

use App\Entity\ItemSlot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
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

        $i = 1;

        foreach ($slots as $slot) {
            $object = (new ItemSlot())
                ->setName($slot['name'])
                ->setNameId($slot['nameId'])
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