<?php

namespace App\Form\Type;

use App\Entity\Hero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class CreateHeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez renseigner le mot de passe.",
                    ]),
                ],
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
            ->add('age', IntegerType::class, [
                'attr' => [
                    'min' => 0
                ],
                'constraints' => [
                    new Positive([
                        'message' => 'L\'âge doit toujours être positif !'
                    ])
                ]
            ])
            ->add('isMale', ChoiceType::class, [
                'choices' => [
                    'Homme' => true,
                    'Femme' => false,
                ]
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