<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gameId = $options['gameId'];
        $firstPlayerName = $options['firstPlayerName'];
        $secondPlayerName = $options['secondPlayerName'];

        $builder
            ->add("choices$gameId", ChoiceType::class, [
                'choices' => [
                    $firstPlayerName => 1,
                    $secondPlayerName => 2,
                ]
            ])
            ->add("save$gameId", SubmitType::class, ['label' => "End game $gameId"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'gameId' => 0,
            'firstPlayerName' => null,
            'secondPlayerName' => null,
        ]);
    }
}
