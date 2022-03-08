<?php

namespace App\Form;

use App\Entity\Sponsors;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class SponsorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'label' => 'Nom de Sponsor',
                'required' => false
                ])

           
            ->add('num',TextType::class, [
                'label' => 'Num de Sponsor',
                'required' => false
                ])
            ->add('budget',TextType::class, [
                'label' => 'Budget',
                'required' => false
                ])

                ->add('imageFile', FileType::class, [
                    'label' => 'L\'image de Sponsor',
                    'mapped' => false,
                    'required' => false
                ])

           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sponsors::class,
        ]);
    }
}
