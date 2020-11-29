<?php

namespace App\Form\Type\SubType;

use App\Entity\BattleItemInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BattleItemInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('armor', IntegerType::class, [
                'required' => false,
            ])
            ->add('trueDamages', IntegerType::class, [
                'required' => false,
            ])
            ->add('weaponType')
            ->add('elementMultipliers', CollectionType::class, [
                'entry_type' => ElementMultiplierType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'block_name' => 'elementMultipliers',
            ])
            ->add('statBonusPercents')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BattleItemInfo::class,
        ]);
    }
}