<?php

namespace App\Form\Type\SubType;

use App\Entity\FightingSkillInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FightingSkillInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customEffects')
            ->add('accuracy', IntegerType::class, [
                'required' => false
            ])
            ->add('isCriticalRateUpgraded', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default d-block',
                ],
                'label' => 'Taux critique augmenté',
                'required' => false
            ])
            ->add('isIgnoreDefense', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default d-block',
                ],
                'label' => 'Ignore la défense',
                'required' => false
            ])
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
            ->add('battleStates', CollectionType::class, [
                'entry_type' => FSBattleStateType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'block_name' => 'battleStates',
            ])
            ->add('element')
            ->add('needWeaponType')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FightingSkillInfo::class,
        ]);
    }
}