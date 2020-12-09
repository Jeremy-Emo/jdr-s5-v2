<?php

namespace App\Form\Type;

use App\Entity\FighterItem;
use App\Entity\Item;
use App\Form\Type\SubType\FighterItemType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StuffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('weapons', EntityType::class, [
                'multiple' => true,
                'mapped' => false,
                'class' => Item::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('i')
                        ->join('i.battleItemInfo', 'bti')
                        ->join('bti.weaponType', 'wt')
                        ->where('wt.id is not null')
                        ->orderBy('i.name', 'ASC');
                },
                'data' => $this->getWeapons($options['existingStuff']),
            ])
        ;

        foreach ($options['itemSlots'] as $slot) {
            $existingForSlot = null;
            /** @var FighterItem $equippedStuff */
            foreach ($options['existingStuff'] as $equippedStuff) {
                if ($equippedStuff->getIsEquipped() && $slot === $equippedStuff->getItem()->getItemSlot()) {
                    $existingForSlot = $equippedStuff;
                }
            }
            if ($existingForSlot !== false) {
                $builder
                    ->add($slot->getNameId(), FighterItemType::class, [
                        'required' => false,
                        'mapped' => false,
                        'itemSlot' => $slot,
                        'data' => $existingForSlot,
                        'label' => false,
                    ])
                ;
            } else {
                $builder
                    ->add($slot->getNameId(), FighterItemType::class, [
                        'required' => false,
                        'mapped' => false,
                        'itemSlot' => $slot,
                        'label' => false,
                    ])
                ;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'itemSlots' => null,
            'existingStuff' => null,
        ]);
    }

    private function getWeapons(?Collection $existingStuff): Collection
    {
        $return = new ArrayCollection();
        /** @var FighterItem $stuff */
        foreach ($existingStuff as $stuff) {
            if ($stuff->getIsEquipped() && $stuff->getItem()->getBattleItemInfo()->getWeaponType() !== null) {
                $return->add($stuff->getItem());
            }
        }
        return $return;
    }
}