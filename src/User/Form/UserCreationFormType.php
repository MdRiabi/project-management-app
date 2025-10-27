<?php

namespace App\User\Form;

use App\Entity\User; // Assurez-vous d'utiliser le namespace correct pour votre Entité User !
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType as FormChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserCreationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // 1. Définition des Rôles (Pour le champ Select)
        $roles = [
            'Membre de l\'Équipe (Standard)' => 'ROLE_USER',
            'Chef de Projet (PM)' => 'ROLE_PM',
            'Product Owner (PO)' => 'ROLE_PO',
            'Client/Stakeholder' => 'ROLE_CLIENT',
            'Super Admin' => 'ROLE_ADMIN',
        ];

        $builder
            // ----------------------------------------
            // 1. Identification (Champs Simples)
            // ----------------------------------------
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => true,
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Date Of Birth',
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => true,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe ne doit pas être vide.']),
                    new Length(['min' => 8, 'minMessage' => 'Le mot de passe doit contenir au moins 8 caractères.']),
                ],
            ])
            ->add('employeeId', TextType::class, [
                'label' => 'Employee ID',
                'required' => true,
            ])

            // ----------------------------------------
            // 2. Rôles et Ressources
            // ----------------------------------------
            ->add('jobTitle', FormChoiceType::class, [
                'label' => 'Job Title',
                'required' => true,
                'choices' => [
                    'Développeur' => 'developer',
                    'Chef de Projet' => 'project_manager',
                    'Designer' => 'designer',
                    'Analyste' => 'analyst',
                    'Testeur' => 'tester',
                ],
            ])
            ->add('department', FormChoiceType::class, [
                'label' => 'Department',
                'required' => true,
                'choices' => [
                    'IT' => 'it',
                    'RH' => 'hr',
                    'Finance' => 'finance',
                    'Marketing' => 'marketing',
                    'Ventes' => 'sales',
                ],
            ])
            ->add('team', FormChoiceType::class, [
                'label' => 'Team',
                'required' => true,
                'choices' => [
                    'Équipe A' => 'team_a',
                    'Équipe B' => 'team_b',
                    'Équipe C' => 'team_c',
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Role',
                'required' => true,
                'choices' => $roles,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('capacityHours', NumberType::class, [
                'label' => 'Weekly Capacity (in Hours)',
                'required' => true,
                'scale' => 2,
                'html5' => true,
                'attr' => ['min' => 0, 'max' => 40],
            ])
            ->add('skills', FormChoiceType::class, [
                'label' => 'Skills',
                'required' => true,
                'choices' => [
                    'PHP' => 'php',
                    'JavaScript' => 'javascript',
                    'Python' => 'python',
                    'Java' => 'java',
                    'SQL' => 'sql',
                    'HTML/CSS' => 'html_css',
                    'Symfony' => 'symfony',
                    'React' => 'react',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('manager', FormChoiceType::class, [
                'label' => 'Manager N+1',
                'required' => true,
                'choices' => [], // Will be populated dynamically
                'placeholder' => 'Sélectionner un manager',
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Activ',
                'required' => false,
            ])

            // ----------------------------------------
            // 3. Fichiers et Messages
            // ----------------------------------------
            ->add('profilePicture', FileType::class, [
                'label' => 'Profile Picture',
                'required' => true,
                'mapped' => false,
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'required' => true,
            ])
            ->add('creationDate', DateType::class, [
                'label' => 'Creation Date',
                'required' => true,
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Spécifie l'Entité à laquelle ce formulaire est lié
            'data_class' => User::class, 
            
            // Ceci est TRÈS important pour l'Admin : on laisse la validation pour le mot de passe
            // qui n'est pas mappé à l'Entité, mais on l'ajoute manuellement à l'objet User.
            'empty_data' => function (FormBuilderInterface $form) {
                return new User();
            },
        ]);
    }
    
    // Ajout d'une méthode pour récupérer le nom du bloc (optionnel mais propre)
    public function getBlockPrefix()
    {
        return 'user_creation';
    }
}
