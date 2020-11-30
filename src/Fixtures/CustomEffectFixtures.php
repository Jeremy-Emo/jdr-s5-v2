<?php

namespace App\Fixtures;

use App\Entity\CustomEffect;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;

class CustomEffectFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $effects = [
            [
                'name' => 'Drain de vie %',
                'nameId' => 'drain_life_percent',
                'value' => 15
            ]
        ];

        $i = 1;

        foreach ($effects as $effect) {
            $object = (new CustomEffect())
                ->setName($effect['name'])
                ->setNameId($effect['nameId'])
                ->setValue($effect['value'])
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