<?php

namespace App\Repository;

use App\Entity\SkillTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SkillTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkillTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkillTag[]    findAll()
 * @method SkillTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkillTag::class);
    }

    // /**
    //  * @return SkillTag[] Returns an array of SkillTag objects
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
    public function findOneBySomeField($value): ?SkillTag
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
