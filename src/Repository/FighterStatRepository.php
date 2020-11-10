<?php

namespace App\Repository;

use App\Entity\FighterStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FighterStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method FighterStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method FighterStat[]    findAll()
 * @method FighterStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FighterStatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FighterStat::class);
    }

    // /**
    //  * @return FighterStat[] Returns an array of FighterStat objects
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
    public function findOneBySomeField($value): ?FighterStat
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
