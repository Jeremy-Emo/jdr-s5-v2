<?php

namespace App\Repository;

use App\Entity\StatBonusPercent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatBonusPercent|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatBonusPercent|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatBonusPercent[]    findAll()
 * @method StatBonusPercent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatBonusPercentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatBonusPercent::class);
    }

    // /**
    //  * @return StatBonusPercent[] Returns an array of StatBonusPercent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatBonusPercent
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
