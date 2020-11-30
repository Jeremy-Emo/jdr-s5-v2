<?php

namespace App\Repository;

use App\Entity\HeroMoney;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HeroMoney|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeroMoney|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeroMoney[]    findAll()
 * @method HeroMoney[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeroMoneyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeroMoney::class);
    }

    // /**
    //  * @return HeroMoney[] Returns an array of HeroMoney objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HeroMoney
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
