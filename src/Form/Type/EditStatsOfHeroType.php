<?php

namespace App\Form\Type;

use App\Entity\FighterInfos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditStatsOfHeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentHP')
            ->add('currentMP')
            ->add('currentSP')
            ->add('currentShieldValue')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FighterInfos::class,
        ]);
    }
}