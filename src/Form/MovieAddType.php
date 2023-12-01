<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title', TextType::class,[
                'attr'=>['class'=>'form-control'],
            ])
            ->add('ReleaseYear', TextType::class,[

                'attr'=>['class'=>'form-control'],
            ])
            ->add('director', TextType::class,[
                'mapped' =>false,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder' => 'Enter Director name, for multiple use a comma between'
                ],
            ])
            ->add('actors', TextType::class,[
                'mapped' =>false,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder' => 'Enter Actor name, for multiple use a comma between'
                ],
            ])
            ->add('runTime', TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder' => 'Enter runtime in minutes'
                ],
            ])
            ->add('ShortDescription', TextType::class,[
                'attr'=>['class'=>'form-control'],
            ])
            ->add('Image', FileType::class, [
                'attr' => ['class' => 'form-control '],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
