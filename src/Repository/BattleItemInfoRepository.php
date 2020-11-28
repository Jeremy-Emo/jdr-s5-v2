<?php

namespace App\Repository;

use App\Entity\BattleItemInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BattleItemInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method BattleItemInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method BattleItemInfo[]    findAll()
 * @method BattleItemInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BattleItemInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BattleItemInfo::class);
    }

    // /**
    //  * @return BattleItemInfo[] Returns an array of BattleItemInfo objects
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
    public function findOneBySomeField($value): ?BattleItemInfo
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
