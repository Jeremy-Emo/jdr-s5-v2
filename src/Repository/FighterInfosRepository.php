<?php

namespace App\Repository;

use App\Entity\FighterInfos;
use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FighterInfos|null find($id, $lockMode = null, $lockVersion = null)
 * @method FighterInfos|null findOneBy(array $criteria, array $orderBy = null)
 * @method FighterInfos[]    findAll()
 * @method FighterInfos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FighterInfosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FighterInfos::class);
    }

    public function findAllSkillsAvailable(FighterInfos $fighter)
    {
        $qb1 = $this->_em->createQueryBuilder();
        $qb1
            ->select('s3')
            ->from(Skill::class, 's3')
            ->where('s3.needSkill is not null')
        ;
        $skillsToFilter = $qb1->getQuery()->getResult();

        $qb2 = $this->_em->createQueryBuilder();
        $qb2
            ->select('s2')
            ->from(Skill::class, 's2')
            ->where('s2.needSkill is null')
        ;
        $allSkills = $qb2->getQuery()->getResult();

        /** @var Skill $skill */
        foreach ($skillsToFilter as $skill) {
            $fighterSkills = $fighter->getSkills();
            foreach ($fighterSkills as $fighterSkill) {
                if (
                    $fighterSkill->getSkill()->getId() === $skill->getNeedSkill()->getId()
                    && $skill->getNeededSkillLevel() <= $fighterSkill->getLevel()
                ) {
                    $allSkills[] = $skill;
                }
            }
        }

        return $allSkills;
    }
}
