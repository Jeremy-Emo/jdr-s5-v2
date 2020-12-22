<?php

namespace App\Form\Type;

use App\Entity\Familiar;
use App\Form\Type\SubType\FighterInfosType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaveFamiliarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('elementAffinity')
            ->add('needLeaderShip')
            ->add('fighterInfos', FighterInfosType::class, [
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Familiar::class,
        ]);
    }
}