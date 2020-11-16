<?php

namespace App\Form\Type\SubType;

use App\Entity\ElementMultiplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElementMultiplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('element')
            ->add('isResistance', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default ma-auto',
                ],
                'label' => 'Résistance',
                'required' => false
            ])
            ->add('value')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ElementMultiplier::class,
        ]);
    }
}