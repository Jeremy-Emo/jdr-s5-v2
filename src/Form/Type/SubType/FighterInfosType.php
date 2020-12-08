<?php

namespace App\Form\Type\SubType;

use App\Entity\FighterInfos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FighterInfosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stats', CollectionType::class, [
                'entry_type' => FighterStatType::class,
                'entry_options'  => [
                    'label' => false,
                ],
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FighterInfos::class,
        ]);
    }
}