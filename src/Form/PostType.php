<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Intitulé du poste',
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => '5',
                        'max' => '255',
                        'minMessage' => 'L\'intitulé doit faire minimum 5 caractères.',
                        'maxMessage' => 'L\'intitulé doit faire maximum 255 caractères.'
                    ])
                ]
            ])
            ->add('workplace', TextType::class, [
                'label' => 'Ville de travail',
                'required' => true,
                'constraints' => [
                    new Length(
                        [
                            'max' => '60',
                            'maxMessage' => 'La ville doit faire maximum 60 caractères.'
                        ]
                    ),
                    // ne doit pas être vide
                    new NotBlank([
                        'message' => 'Vous devez renseigner un lieu de travail.'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description détaillée',
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => '5',
                        'max' => '500',
                        'minMessage' => 'La description doit faire minimum 5 caractères.',
                        'maxMessage' => 'La description doit faire maximum 500 caractères.'
                    ])
                ]
            ])
            ->add('schedule', TextType::class, [
                'label' => 'Horaires',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Les horaires doivent être renseigné.'
                    ])
                ]
            ])
            ->add('salary', TextareaType::class, [
                'label' => 'Taux horaire',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le taux horaire doit être renseigné.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
