<?php

namespace App\Form;

use App\Entity\Produits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class RechercheProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', null, array('label' => false))
            ->add('longdescription' ,HiddenType::class, [
                'data' => 'abcdef',
            ])
            ->add('prix' ,HiddenType::class, [
                'data' => '10',
            ])
            ->add('ref' ,HiddenType::class, [
                'data' => '10',
            ])
            ->add('Recherche',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
