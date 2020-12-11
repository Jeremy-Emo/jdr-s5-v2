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
}
