<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('duration')
            ->add('rating')
            ->add('summary')
            ->add('synopsis')
            ->add('releaseDate')
            ->add('country')
            ->add('poster')
            ->add('type', EntityType::class, [
                "multiple" => false,
                "expanded" => false,
                "class" => Type::class,
                'choice_label' => 'name',

            ])
            ->add('genres', EntityType::class, [
                    "multiple" => true,
                    "expanded" => true, 
                    "class" => Genre::class,
                    'choice_label' => 'name',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
