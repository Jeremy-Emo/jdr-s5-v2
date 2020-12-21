<?php

namespace App\Form\Type\SubType;

use App\Entity\ConsumableEffect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumableEffectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('editHP')
            ->add('editMP')
            ->add('editSP')
            ->add('editStatPoints')
            ->add('editSkillPoints')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsumableEffect::class,
        ]);
    }
}