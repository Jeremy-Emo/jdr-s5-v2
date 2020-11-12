<?php

namespace App\Repository;

use App\Entity\FightingSkillInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FightingSkillInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method FightingSkillInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method FightingSkillInfo[]    findAll()
 * @method FightingSkillInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FightingSkillInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FightingSkillInfo::class);
    }

    // /**
    //  * @return FightingSkillInfo[] Returns an array of FightingSkillInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FightingSkillInfo
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
