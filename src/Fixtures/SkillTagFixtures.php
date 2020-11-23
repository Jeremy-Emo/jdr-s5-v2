<?php


namespace App\Fixtures;


use App\Entity\SkillTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
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

        $i = 1;

        foreach ($skillTagsToLoad as $skillTag) {
            $object = (new SkillTag())
                ->setName($skillTag['name'])
                ->setNameId($skillTag['nameId'])
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