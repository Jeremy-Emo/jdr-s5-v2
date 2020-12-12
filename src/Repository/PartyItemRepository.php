<?php

namespace App\Repository;

use App\Entity\PartyItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PartyItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method PartyItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method PartyItem[]    findAll()
 * @method PartyItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartyItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PartyItem::class);
    }

    // /**
    //  * @return PartyItem[] Returns an array of PartyItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PartyItem
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
