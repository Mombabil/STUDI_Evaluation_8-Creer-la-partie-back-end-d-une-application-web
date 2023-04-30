<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserRecruteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // pour les recruteurs
            ->add('enterprise', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => '3',
                        'max' => '30',
                        'minMessage' => 'Le nom doit faire minimum 3 caractères.',
                        'maxMessage' => 'L\'intitulé doit faire maximum 30 caractères.'
                    ])
                ]
            ])
            ->add('enterpriseAdress', TextType::class, [
                'label' => 'Adresse de l\'entreprise',
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => '5',
                        'max' => '255',
                        'minMessage' => 'L\'adresse doit faire minimum 5 caractères.',
                        'maxMessage' => 'L\'adresse doit faire maximum 255 caractères.'
                    ])
                ]
            ])
            // validation
            ->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
