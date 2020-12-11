<?php

namespace App\Form\Type\SubType;

use App\Entity\Reward;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RewardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('completionRank')
            ->add('statPoints', IntegerType::class, [
                'required' => false,
            ])
            ->add('skillPoints', IntegerType::class, [
                'required' => false,
            ])
            ->add('randomItem')
            ->add('items')
            ->add('skills')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reward::class,
        ]);
    }
}