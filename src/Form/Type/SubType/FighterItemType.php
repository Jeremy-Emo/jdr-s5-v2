<?php

namespace App\Form\Type\SubType;

use App\Entity\FighterItem;
use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FighterItemType extends AbstractType
{
    /** @required  */
    public ItemRepository $itemRepository;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $slot = $options['itemSlot'];
        $builder
            ->add('item', EntityType::class, [
                'class' => Item::class,
                'label' => $slot ?? null,
                'required' => false,
                'choices' => $this->itemRepository->findBy([
                    'itemSlot' => $slot
                ]),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FighterItem::class,
            'itemSlot' => null,
        ]);
    }
}