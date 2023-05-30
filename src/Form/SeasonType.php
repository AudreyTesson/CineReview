<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Season;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('nbEpisodes')
            ->add('movie', EntityType::class, [
                "multiple" => false,
                "expanded" => false,
                "class" => Movie::class,
                "choice_label" => "title",
                "query_builder" => function(EntityRepository $entityrepository){
                    return $entityrepository->createQueryBuilder('m')
                        ->join("m.type", "t")
                        ->where("t.name = 'série'")
                        ->orderBy('m.title', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
