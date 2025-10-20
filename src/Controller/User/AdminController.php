<?php

namespace App\Controller\User; // Assurez-vous d'utiliser le namespace correct pour votre dossier src/User/

use App\Entity\User; // Assurez-vous d'utiliser le bon namespace pour l'entité User
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')] // Règle Métier : Seul l'Admin peut accéder à ce contrôleur
class AdminController extends AbstractController
{
    /**
     * Affiche la liste complète des utilisateurs du système.
     */
    #[Route('/admin/users', name: 'app_admin_users_list')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupérer tous les utilisateurs (pour le tableau)
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Affiche le formulaire de création d'un nouvel utilisateur.
     * Cette route sera liée à la fonctionnalité 'Créer un Compte' par l'Admin.
     */
    #[Route('/admin/users/new', name: 'app_admin_users_new')]
    public function new(): Response
    {
        // ... Logique pour générer et afficher le formulaire de création (sera fait plus tard) ...
        return $this->render('user/new.html.twig', [
            // ...
        ]);
    }
}