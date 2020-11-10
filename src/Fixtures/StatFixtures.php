<?php


namespace App\Fixtures;


use App\Entity\Stat;
use App\Manager\StatManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $statsToSave = [
            [
                'name' => 'Force',
                'description' => "Augmente vos dégâts physique et votre capacité à soulever des objets",
                'nameId' => StatManager::STRENGTH
            ],
            [
                'name' => 'Vitalité',
                'description' => "Augmente vos points de vie et votre capacité à les régénérer",
                'nameId' => StatManager::STAMINA
            ],
            [
                'name' => 'Intelligence',
                'description' => "Augmente vos dégâts magiques",
                'nameId' => StatManager::INTELLIGENCE
            ],
            [
                'name' => 'Sagesse',
                'description' => "Augmente vos points de mana et votre capacité à les régénérer",
                'nameId' => StatManager::WISDOM
            ],
            [
                'name' => 'Agilité',
                'description' => "Augmente votre capacité d'esquive",
                'nameId' => StatManager::AGILITY
            ],
            [
                'name' => 'Taux critique',
                'description' => "Augmente vos chances d'effectuer des coups critiques",
                'nameId' => StatManager::CRITICAL_RATE
            ],
            [
                'name' => 'Perception',
                'description' => "Augmente votre capacité à repérer des choses",
                'nameId' => StatManager::PERCEPTION
            ],
            [
                'name' => 'Charisme',
                'description' => "Augmente votre beauté et votre leadership",
                'nameId' => StatManager::CHARISMA
            ],
            [
                'name' => 'Résistance',
                'description' => "Augmente votre capacité à résister aux effets néfastes",
                'nameId' => StatManager::RESISTANCE
            ],
        ];

        foreach ($statsToSave as $stat) {
            $object = (new Stat())
                ->setName($stat['name'])
                ->setDescription($stat['description'])
                ->setNameId($stat['nameId'])
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }
}