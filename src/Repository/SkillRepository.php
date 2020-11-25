<?php

namespace App\Repository;

use App\Entity\FighterInfos;
use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Skill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Skill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Skill[]    findAll()
 * @method Skill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Skill::class);
    }

    public function findOneByRandom(int $max)
    {
        $result = $this->createQueryBuilder('s')
            ->andWhere('s.cost <= :max')
            ->setParameter('max', $max)
            ->getQuery()
            ->getResult()
        ;
        return $result[array_rand($result)];
    }
}
