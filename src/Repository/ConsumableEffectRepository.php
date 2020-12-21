<?php

namespace App\Repository;

use App\Entity\ConsumableEffect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsumableEffect|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsumableEffect|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsumableEffect[]    findAll()
 * @method ConsumableEffect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsumableEffectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsumableEffect::class);
    }

    // /**
    //  * @return ConsumableEffect[] Returns an array of ConsumableEffect objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConsumableEffect
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
