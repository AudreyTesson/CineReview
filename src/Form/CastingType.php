<?php

namespace App\Form;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Person;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CastingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role')
            ->add('creditOrder')
            ->add('person', EntityType::class, [
                "multiple" => false,
                "expanded" => false,
                "class" => Person::class,
                "choice_label" => "fullname"
                
            ])
            ->add('movie', EntityType::class, [
                "multiple" => false,
                "expanded" => false,
                "class" => Movie::class,
                'choice_label' => 'title',
                "query_builder" => function(EntityRepository $entityrepository){
                    return $entityrepository->createQueryBuilder('m')
                        ->orderBy('m.title', 'ASC');
                    }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Casting::class,
        ]);
    }
}
