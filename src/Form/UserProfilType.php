<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class UserProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // pour les candidats
            ->add('type', TextType::class, [
                'label' => 'Métier recherché',
                'constraints' => [
                    new Length([
                        'max' => '30',
                        'maxMessage' => 'Le métier doit faire maximum 30 caractères.'
                    ])
                ]
            ])
            ->add('cv', FileType::class, [
                'label' => 'Votre cv',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf'
                        ],
                        'mimeTypesMessage' => 'Veuillez proposer un cv au format pdf uniquement.',
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
