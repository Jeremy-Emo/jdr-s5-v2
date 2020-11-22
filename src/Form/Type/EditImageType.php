<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class EditImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
}