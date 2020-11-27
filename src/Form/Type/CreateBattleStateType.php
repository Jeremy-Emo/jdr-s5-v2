<?php

namespace App\Form\Type;

use App\Entity\BattleState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateBattleStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez renseigner le nom du statut.",
                    ]),
                ],
            ])
            ->add('nameId', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez renseigner l'identifiant du statut'.",
                    ]),
                ],
            ])
            ->add('isTransformation', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default d-block',
                ],
                'label' => 'Transformation',
                'required' => false
            ])
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
                'required' => true,
                'label' => 'Image',
                'attr' => [
                    'class' => 'dropify',
                    'data-max-file-size' => '3M',
                    'data-allowed-file-extensions' => 'jpg jpeg png'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BattleState::class,
        ]);
    }
}