<?php

namespace App\Form\Type;

use App\Entity\FighterInfos;
use App\Form\Type\SubType\FighterSkillType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FighterManageSkillsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('skills', CollectionType::class, [
            'entry_type' => FighterSkillType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true,
            'label' => false,
            'block_name' => 'skills',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FighterInfos::class,
        ]);
    }
}