<?php

namespace App\Repository;

use App\Entity\FighterSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FighterSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method FighterSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method FighterSkill[]    findAll()
 * @method FighterSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FighterSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FighterSkill::class);
    }

    // /**
    //  * @return FighterSkill[] Returns an array of FighterSkill objects
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
    public function findOneBySomeField($value): ?FighterSkill
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
