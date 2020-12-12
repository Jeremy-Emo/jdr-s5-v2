<?php

namespace App\Repository;

use App\Entity\BattleTurn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BattleTurn|null find($id, $lockMode = null, $lockVersion = null)
 * @method BattleTurn|null findOneBy(array $criteria, array $orderBy = null)
 * @method BattleTurn[]    findAll()
 * @method BattleTurn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BattleTurnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BattleTurn::class);
    }

    // /**
    //  * @return BattleTurn[] Returns an array of BattleTurn objects
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
    public function findOneBySomeField($value): ?BattleTurn
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
