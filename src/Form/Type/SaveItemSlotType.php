<?php

namespace App\Form\Type;

use App\Entity\ItemSlot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaveItemSlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('nameId')
            ->add('isForFamiliar', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default d-block',
                ],
                'label' => 'Slot de familier',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ItemSlot::class,
        ]);
    }
}