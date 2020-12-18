<?php

namespace App\Form\Type;

use App\Entity\Hero;
use App\Entity\Party;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SavePartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('heroes', EntityType::class, [
                'multiple' => true,
                'class' => Hero::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('h')
                        ->join('h.account', 'a')
                        ->where('a.id != :idAccount')
                        ->andWhere('h.isDead = false')
                        ->setParameter('idAccount', $options['id'])
                        ->orderBy('h.name', 'ASC');
                },
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Party::class,
            'isEdit' => false,
            'id' => null,
        ]);
    }
}