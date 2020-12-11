<?php

namespace App\Form\Type;

use App\Entity\Quest;
use App\Form\Listener\CheckQuestCompletionListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompleteQuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('completionRank')
            ->add('isFailed', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'pretty p-default',
                ],
                'label' => 'Est un échec',
                'required' => false
            ])
        ;

        $builder->addEventSubscriber(new CheckQuestCompletionListener());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quest::class,
        ]);
    }
}