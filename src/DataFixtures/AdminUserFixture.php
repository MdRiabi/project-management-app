<?php

// src/DataFixtures/AdminUserFixture.php

namespace App\DataFixtures;

use Entity\User\User; // Assurez-vous que le namespace correspond à votre classe User !
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface; // Potentiellement utile, mais pas indispensable ici

class AdminUserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    // Injectez le service UserPasswordHasherInterface pour hacher le mot de passe
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- Création du Super Admin ---
        $superAdmin = new User();

        // Informations de Connexion & Profil
        $superAdmin->setEmail('super.admin@votre-domaine.com');
        $superAdmin->setFirstName('Super');
        $superAdmin->setLastName('Admin');
        $superAdmin->setJobTitle('Super Administrator');
        $superAdmin->setDepartment('IT');
        $superAdmin->setCapacityHours(40.00); // Capacité pour l'admin
        $superAdmin->setIsActive(true); // Compte actif par défaut

        // Hachage du mot de passe - REMPLACEZ 'MotDePasseSuperAdmin2025!' PAR UN MOT DE PASSE SÉCURISÉ ET TEMPORAIRE !
        $hashedPassword = $this->passwordHasher->hashPassword(
            $superAdmin,
            'MotDePasseSuperAdmin2025!' // <<<<<<<<<<<<<<<<< MODIFIEZ CE MOT DE PASSE !
        );
        $superAdmin->setPassword($hashedPassword);

        // Attribution des rôles - C'est ce qui donne TOUS les droits
        $superAdmin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $manager->persist($superAdmin);
        $this->addReference('super_admin', $superAdmin); // Pour le réutiliser dans d'autres fixtures si besoin

        // --- Création d'un utilisateur Membre par défaut (pour les tests) ---
        $member = new User();
        $member->setEmail('membre.test@votre-domaine.com');
        $member->setFirstName('Membre');
        $member->setLastName('Test');
        $member->setJobTitle('Développeur');
        $member->setDepartment('Development');
        $member->setCapacityHours(35.00);
        $member->setIsActive(true);

        // Mot de passe pour le membre
        $hashedPasswordMember = $this->passwordHasher->hashPassword($member, 'membre123');
        $member->setPassword($hashedPasswordMember);

        // Rôle de base pour un membre
        $member->setRoles(['ROLE_USER']);

        $manager->persist($member);
        $this->addReference('member_user', $member);

        // Sauvegarde finale dans la base de données
        $manager->flush();
    }
}
