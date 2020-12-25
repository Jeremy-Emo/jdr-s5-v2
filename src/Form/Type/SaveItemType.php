<?php

namespace App\Form\Type;

use App\Entity\Item;
use App\Form\Type\SubType\BattleItemInfoType;
use App\Form\Type\SubType\ConsumableEffectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SaveItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('rarity')
            ->add('description')
            ->add('maxDurability')
            ->add('file', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '3072k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Merci de téléverser une image correspondant aux critères.',
                    ])
                ],
                'required' => !$options['isEdit'],
                'label' => 'Image',
                'attr' => [
                    'class' => 'dropify',
                    'data-max-file-size' => '3M',
                    'data-allowed-file-extensions' => 'jpg jpeg png'
                ],
            ])
            ->add('customEffect')
            ->add('itemSlot')
            ->add('battleItemInfo', BattleItemInfoType::class, [
                'required' => false,
            ])
            ->add('isConsumable', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default d-block',
                ],
                'label' => 'Consommable',
                'required' => false
            ])
            ->add('isNotRandomizable', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default d-block',
                ],
                'label' => 'Ne peut être obtenu aléatoirement',
                'required' => false
            ])
            ->add('consumableEffect', ConsumableEffectType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'isEdit' => false,
        ]);
    }
}