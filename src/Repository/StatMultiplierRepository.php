<?php

namespace App\Repository;

use App\Entity\StatMultiplier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatMultiplier|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatMultiplier|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatMultiplier[]    findAll()
 * @method StatMultiplier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatMultiplierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatMultiplier::class);
    }

    // /**
    //  * @return StatMultiplier[] Returns an array of StatMultiplier objects
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
    public function findOneBySomeField($value): ?StatMultiplier
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
