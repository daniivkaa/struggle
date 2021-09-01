<?php

namespace App\Form;

use App\Entity\Competition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('address')
            ->add('day')
            ->add('time')
            ->add("public", ChoiceType::class, [
                'choices' => [
                    "Закрытое" => false,
                    "Публичное" => true,
                ]
            ])
            ->add("type", ChoiceType::class, [
                'choices' => [
                    "Круговое" => "circle",
                    "Олимпийское" => "olimp",
                ]
            ])
            ->add("double", ChoiceType::class, [
                'choices' => [
                    "Обычное" => false,
                    "Двойное" => true,
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Competition::class,
        ]);
    }
}
