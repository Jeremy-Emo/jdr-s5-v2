<?php

namespace App\Repository;

use App\Entity\BattleState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BattleState|null find($id, $lockMode = null, $lockVersion = null)
 * @method BattleState|null findOneBy(array $criteria, array $orderBy = null)
 * @method BattleState[]    findAll()
 * @method BattleState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BattleStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BattleState::class);
    }

    // /**
    //  * @return BattleState[] Returns an array of BattleState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BattleState
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
