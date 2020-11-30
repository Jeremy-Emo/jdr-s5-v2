<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\ItemSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findRandomByItemSlotAndRarity(ItemSlot $itemSlot, string $rarity)
    {
        $items = $this->createQueryBuilder('i')
            ->join('i.itemSlot', 'isl')
            ->join('i.rarity', 'r')
            ->andWhere('r.name = :rarity')
            ->setParameter('rarity', $rarity)
            ->andWhere('isl.id = :id')
            ->setParameter('id', $itemSlot->getId())
            ->getQuery()
            ->getResult()
        ;

        return !empty($items) ? $items[array_rand($items)] : null;
    }

    public function findRandomWeaponByRarity(string $rarity)
    {
        $items = $this->createQueryBuilder('i')
            ->join('i.battleItemInfo', 'bif')
            ->join('bif.weaponType', 'wt')
            ->join('i.rarity', 'r')
            ->andWhere('r.name = :rarity')
            ->setParameter('rarity', $rarity)
            ->getQuery()
            ->getResult()
        ;

        return !empty($items) ? $items[array_rand($items)] : null;
    }
}
