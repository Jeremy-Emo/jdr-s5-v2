<?php

namespace App\Form\Type;

use App\Entity\Monster;
use App\Form\Type\SubType\FighterInfosType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaveMonsterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('isFinished', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default d-block',
                ],
                'label' => 'Terminé',
                'required' => false
            ])
            ->add('elementAffinity')
            ->add('fighterInfos', FighterInfosType::class, [
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Monster::class,
        ]);
    }
}