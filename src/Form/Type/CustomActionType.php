<?php

namespace App\Form\Type;

use App\Entity\BattleTurn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('action')
            ->add('battleState', TextareaType::class, [
                'attr' => [
                    'class' => 'jsonPrettify',
                    'rows' => 60,
                ],
            ])
        ;

        $builder
            ->get('battleState')
            ->addModelTransformer(new CallbackTransformer(
                function ($dataAsArray) {
                    return json_encode($dataAsArray);
                },
                function ($dataAsString) {
                    return json_decode($dataAsString, true);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BattleTurn::class,
        ]);
    }
}