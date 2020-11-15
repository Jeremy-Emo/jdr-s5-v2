<?php

namespace App\Form\Type\SubType;

use App\Entity\FightingSkillInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FightingSkillInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customEffects')
            ->add('elementsMultipliers', CollectionType::class, [
                'entry_type' => ElementMultiplierType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'block_name' => 'elementMultipliers',
            ])
            ->add('statMultipliers', CollectionType::class, [
                'entry_type' => StatMultiplierType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'block_name' => 'statMultipliers',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FightingSkillInfo::class,
        ]);
    }
}