<?php

namespace App\Form\Type\SubType;

use App\Entity\FighterStat;
use App\Entity\Stat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FighterStatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stat', EntityType::class, [
                'class' => Stat::class,
                'attr' => [
                    'disabled' => 'disabled'
                ],
            ])
            ->add('value')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FighterStat::class,
        ]);
    }
}