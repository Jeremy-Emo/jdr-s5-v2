<?php

namespace App\Form\Type;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminEditAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isMJ', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default',
                ],
                'label' => 'Maître du jeu',
                'required' => false
            ])
            ->add('isAdmin', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default',
                ],
                'label' => 'Administrateur',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}