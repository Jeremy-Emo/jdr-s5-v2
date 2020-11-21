<?php

namespace App\Repository;

use App\Entity\AccountSkills;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountSkills|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountSkills|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountSkills[]    findAll()
 * @method AccountSkills[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountSkillsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountSkills::class);
    }

    // /**
    //  * @return AccountSkills[] Returns an array of AccountSkills objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccountSkills
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
