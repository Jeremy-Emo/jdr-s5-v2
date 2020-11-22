<?php

namespace App\Repository;

use App\Entity\ItemSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemSlot[]    findAll()
 * @method ItemSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemSlot::class);
    }

    // /**
    //  * @return ItemSlot[] Returns an array of ItemSlot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemSlot
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
