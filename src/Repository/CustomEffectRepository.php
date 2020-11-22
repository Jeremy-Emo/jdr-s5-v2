<?php

namespace App\Repository;

use App\Entity\CustomEffect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomEffect|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomEffect|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomEffect[]    findAll()
 * @method CustomEffect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomEffectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomEffect::class);
    }
}
