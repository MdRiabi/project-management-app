<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * Gère l'affichage du formulaire de connexion et le processus d'authentification.
     * Cette méthode est gérée par Symfony Security pour la vérification du mot de passe.
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, le rediriger vers la route de redirection post-login
        if ($this->getUser()) {
            return $this->redirectToRoute('app_post_login_redirect'); 
        }

        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Récupère le dernier email saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Home/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Gère la redirection après une connexion réussie, en fonction du rôle.
     * C'est le point d'entrée après le login.
     */
    #[Route(path: '/post-login-redirect', name: 'app_post_login_redirect')]
    #[IsGranted('ROLE_USER')] // Assure que seuls les utilisateurs connectés peuvent y accéder
    public function postLoginRedirect(): Response
    {
        // Le rôle le plus élevé détermine l'espace de travail

        // 1. Redirection pour le Super Admin / Admin
        if ($this->isGranted('ROLE_ADMIN')) {
            // Destination : Dashboard Global avec tous les KPIs et gestions (Utilisateurs, Risques)
            return $this->redirectToRoute('app_dashboard'); 
        }
        
        // 2. Redirection pour le Chef de Projet
        // (A implémenter plus tard après la création du rôle ROLE_PM et de la route)
        if ($this->isGranted('ROLE_PM')) {
            return $this->redirectToRoute('app_pm_workspace'); 
        }

        // 3. Redirection pour le Membre de l'Équipe
        // (A implémenter plus tard après la création de la route)
        if ($this->isGranted('ROLE_MEMBER')) {
            return $this->redirectToRoute('app_member_tasks_board');
        }
        
        // 4. Redirection par défaut (utilisateur étrange ou sans rôle spécifique)
        // Pour des raisons de sécurité, nous le déconnectons si aucun espace n'est défini
        $this->addFlash('warning', 'Votre rôle est incomplet ou non autorisé. Veuillez contacter l\'administrateur.');
        return $this->redirectToRoute('app_logout');
    }

    /**
     * Gère la déconnexion de l'utilisateur.
     * La logique réelle est gérée par le firewall Symfony (via le check_path_logout dans security.yaml).
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the firewall.');
    }
}

### Étapes à suivre pour que cela fonctionne :

#1.  **Créez** le fichier `src/Controller/SecurityController.php` avec le code ci-dessus.
#2.  **Vérifiez/Créez** le template `templates/security/login.html.twig`.
#3.  **Vérifiez/Créez** le contrôleur `src/Controller/DashboardController.php` avec la route `app_dashboard`.
#4.  **Vérifiez la configuration** dans `config/packages/security.yaml` pour que le `target` pointe vers `app_post_login_redirect`.

#Avec cette structure, le Super Admin se connecte et suit le chemin `app_login` -> `app_post_login_redirect` -> `app_dashboard` sans aucun problème.