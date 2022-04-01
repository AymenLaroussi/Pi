<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Sponsors;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EvenetType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomeven',TextType::class, [
                'label' => 'Nom de l\'évenement',
                'required' => false
            ])
            ->add('lieuevent',TextType::class, [
                'label' => 'Lieu de l\'évenement',
                'required' => false
            ])
            ->add('datevent',DateType::class
            , [
                'label' => 'Date de Début de l\'évenement',
                'widget' => 'single_text',
                'required' => false,
            
            ])
            ->add('sponsors', EntityType::class, [
                'label' => 'Les Sponsors de l\'évenement',
                'class' => Sponsors::class,
                'choice_label' => function ($sponsor) {
                    return $sponsor->getNom();
                },
               'multiple' => true,
               'required' => false
            ])
            
            
            ->add('heurevent',TimeType::class, [
                'label' => 'Heure de l\'évenement',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('datefin',DateType::class, [
                'label' => 'Date Fin de l\'évenement',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('nbrplace',TextType::class, [
                'label' => 'Nombre de place de l\'évenement',
                'required' => false
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'L\'image de l\'évenement',
                'mapped' => false,
                'required' => false
            ])
            ->add('description' ,TextType::class, [
                'label' => 'La Description de l\'évenement',
                
                'required' => false
            ])
            
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
