<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        "message" => "Le nom ne doit pas être vide !"
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        "message" => "Le prénom ne doit pas être vide !"
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new Email([
                        "message" => 'L\'email "{{ value }}" n\'est pas une adresse mail valide.'
                    ])
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Candidat' => 'ROLE_CANDIDAT',
                    'Recruteur' => 'ROLE_RECRUTEUR',
                    'Consultant' => 'ROLE_CONSULTANT',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles'
            ])
            ->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
