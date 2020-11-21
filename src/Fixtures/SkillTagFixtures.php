<?php


namespace App\Fixtures;


use App\Entity\SkillTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SkillTagFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $skillTagsToLoad = [
            [
                'name' => 'Connaissances',
                'nameId' => 'knowledge'
            ]
        ];

        foreach ($skillTagsToLoad as $skillTag) {
            $object = (new SkillTag())
                ->setName($skillTag['name'])
                ->setNameId($skillTag['nameId'])
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