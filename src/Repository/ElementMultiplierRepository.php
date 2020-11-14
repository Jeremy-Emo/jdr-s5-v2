<?php

namespace App\Repository;

use App\Entity\ElementMultiplier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ElementMultiplier|null find($id, $lockMode = null, $lockVersion = null)
 * @method ElementMultiplier|null findOneBy(array $criteria, array $orderBy = null)
 * @method ElementMultiplier[]    findAll()
 * @method ElementMultiplier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementMultiplierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElementMultiplier::class);
    }
}
