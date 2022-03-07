<?php

namespace App\Form;

use App\Entity\Jeu;
use App\Entity\Tournoi;
use App\Repository\JeuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nom', TextType::class ,[
                'attr' => [
                    'placeholder' => "Tapez le nom de tournoi",
                    'class' => 'form-control'
                ]
            ])
            ->add('nbr_equipes', ChoiceType::class,
                array(
                    'choices' => array(
                         2 => 2,
                         4  => 4,
                         8  =>  8,
                        16 =>  16,
                         32 =>  32,
                    )))
            ->add('nbr_joueur_eq', ChoiceType::class,
                array(
                     'choices' => range(1,10)

                    ))
            ->add('prix' ,MoneyType::class, [
                'divisor' => 100,
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false
            ])
            ->add('Jeu', EntityType::class, [
                'class'=>Jeu::class,
                 'choice_label'=>'nom'
            ])
            ->add('discord_channel')
            ->add('time', DateTimeType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                ],
            ])
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournoi::class,
        ]);
    }
}
