<?php

namespace App\Form\Type;

use App\Entity\Skill;
use App\Form\Type\SubType\FightingSkillInfoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SaveSkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez renseigner le nom de la compétence.",
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez renseigner le nom de la compétence.",
                    ]),
                ],
            ])
            ->add('cost', IntegerType::class, [
                'empty_data' => 1,
            ])
            ->add('hpCost', IntegerType::class, [
                'empty_data' => 0,
            ])
            ->add('mpCost', IntegerType::class, [
                'empty_data' => 0,
            ])
            ->add('spCost', IntegerType::class, [
                'empty_data' => 0,
            ])
            ->add('isPassive', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default d-block',
                ],
                'label' => 'Compétence passive',
                'required' => false
            ])
            ->add('needSkill')
            ->add('neededSkillLevel', IntegerType::class, [
                'required' => false
            ])
            ->add('tags')
            ->add('statBonusPercents')
            ->add('isUsableInBattle', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default d-block',
                ],
                'label' => 'Compétence de combat',
                'required' => false
            ])
            ->add('fightingSkillInfo', FightingSkillInfoType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}