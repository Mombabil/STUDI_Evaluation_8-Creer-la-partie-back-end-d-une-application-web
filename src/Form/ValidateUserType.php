<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // le consultant ne peut attribuer que un role candidat ou recruteur seulement aux nouveaux arrivants
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Candidat' => 'ROLE_CANDIDAT',
                    'Recruteur' => 'ROLE_RECRUTEUR',
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles'
            ])
            ->add('valider', SubmitType::class);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
