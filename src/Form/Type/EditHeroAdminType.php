<?php

namespace App\Form\Type;

use App\Entity\Currency;
use App\Entity\Hero;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditHeroAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('elementAffinity')
            ->add('age')
            ->add('isMale', ChoiceType::class, [
                'choices' => [
                    'Homme' => true,
                    'Femme' => false,
                ]
            ])
            ->add('isDead', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default',
                ],
                'label' => 'Mort',
                'required' => false
            ])
            ->add('addStatPoints', IntegerType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('addSkillPoints', IntegerType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('addMoney', EntityType::class, [
                'mapped' => false,
                'multiple' => true,
                'class' => Currency::class,
                'required' => false,
            ])
            ->add('addMoneyAmount', IntegerType::class, [
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hero::class,
        ]);
    }
}