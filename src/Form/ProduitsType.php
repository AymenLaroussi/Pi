<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Categories;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class ,[
                'attr' => [
                  'placeholder' => "Contenu de l'article",
                  'class' => 'form-control'
                  ]
              ])
            ->add('description', TextareaType::class)

            ->add('longdescription', TextareaType::class)
            ->add('stock',NumberType::class,[
                'attr' => ['placeholder' => 'Quantité en stock'],
          ])
          
            ->add('prix' ,MoneyType::class, [
                'divisor' => 100,
            ])
            ->add('promo', NumberType::class ,[
                'attr' => [
                  'placeholder' => "Contenu de l'article",
                  'class' => 'form-control'
                  ]
            ])
            ->add('flash', ChoiceType::class, [

                'choices'  => [                    
                    'Oui' => true,
                    'Non' => false,
                ],
            ])

            ->add('stock', NumberType::class ,[

                'attr' => [
                  'placeholder' => "Contenu de l'article",
                  'class' => 'form-control'
                  ]
              ])

              ->add('ref', TextType::class ,[
                'attr' => [
                  'placeholder' => "Contenu de l'article",
                  'class' => 'form-control'
                  ]
              ])
            
            ->add('categories')
            ->add('imageFile', FileType::class, [
                'mapped' => false
            ])
            ->add('Enregistrer',SubmitType::class, [
                'attr' => [
                    'href' => "{{ path('ajout_categories') }}",
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }

}
