<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Director;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiMovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title', TextType::class)
            ->add('ReleaseYear', TextType::class)
            ->add('ShortDescription', TextType::class)
            ->add('Image', TextType::class)
            ->add('runTime', TextType::class)
            ->add('Actors', TextType::class, [
                'mapped' => false,
            ])
            ->add('directors',TextType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
            'csrf_protection' => false,
        ]);
    }
}
