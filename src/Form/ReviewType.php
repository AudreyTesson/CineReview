<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                "label" => "Votre pseudo:",
                "attr" => [
                    "placeholder" => "votre pseudo ..."
                ]
            ])
            ->add('email', EmailType::class, [
                "label" => "Votre E-mail:",
                "attr" => [
                    "placeholder" => "aloha@hawaï.com"
                ]
            ])
            ->add('content', TextareaType::class, [
                "label" => "Votre commentaire : ",
                "attr" => [
                    "placeholder" => "Il était bien ce film ..."
                ]
            ])
            ->add('rating', ChoiceType::class, [
                'choices'  => [
                    'Excellent' => 5,
                    "Très bon" => 4, 
                    "Bon" => 3,
                    "Peut mieux faire" => 2, 
                    "A éviter" => 1
                ],
                "multiple" => false,
                "expanded" => true,
            ])
            ->add('reactions', ChoiceType::class, [
                "multiple" => true,
                "expanded" => true,
                'choices'  => [
                    'Rire 😂' => "smile",
                    "Pleurer 😭" => "cry", 
                    "Réfléchir 🤔" => "think",
                    "Dormir 😴" => "sleep", 
                    "Rêver 💭" => "dream"
                ],
            ])
            ->add('watchedAt', DateType::class, [
                "widget" => "single_text",
                "input" => "datetime_immutable"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
            "attr" => ["novalidate" => 'novalidate', "class" => "my-css-class"]
        ]);
    }
}
