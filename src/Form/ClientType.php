<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('firstname')
            ->add('phone')
            ->add('sexe', ChoiceType::class,[
                "choices" => [
                    "Masculin" => "Masculin",
                    "Feminin" => "Feminin"
                ]
            ])
            ->add('email')
            ->add('entreprise', EntityType::class,[
                'class' => Entreprise::class,
                //le champ a afficher
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
