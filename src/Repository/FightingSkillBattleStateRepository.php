<?php

namespace App\Repository;

use App\Entity\FightingSkillBattleState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FightingSkillBattleState|null find($id, $lockMode = null, $lockVersion = null)
 * @method FightingSkillBattleState|null findOneBy(array $criteria, array $orderBy = null)
 * @method FightingSkillBattleState[]    findAll()
 * @method FightingSkillBattleState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FightingSkillBattleStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FightingSkillBattleState::class);
    }

    // /**
    //  * @return FightingSkillBattleState[] Returns an array of FightingSkillBattleState objects
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
    public function findOneBySomeField($value): ?FightingSkillBattleState
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
