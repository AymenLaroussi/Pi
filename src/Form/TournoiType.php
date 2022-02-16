<?php

namespace App\Form;

use App\Entity\Tournoi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('nbr_equipes')
            ->add('nbr_joueur_eq')
            ->add('prix')
            ->add('image')
            ->add('discord_channel')
            ->add('time')
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
