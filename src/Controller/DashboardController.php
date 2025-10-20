<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    // C'est l'endpoint qui sera affiché après la connexion réussie.
    // Il est CRUCIAL de protéger cet accès.
    // Nous autorisons ROLE_USER car tout utilisateur (Admin, PM, Membre) aura au moins ROLE_USER
    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')] // Seuls les utilisateurs connectés peuvent y accéder
    public function index(): Response
    {
        // Logique métier pour la dashboard (sera implémentée plus tard) :
        // 1. Récupérer les KPIs globaux (si l'utilisateur est Admin/Sponsor)
        // 2. Récupérer les tâches de l'utilisateur (si c'est un Membre)
        
        // Nous allons déterminer le bon template à afficher.
        $user = $this->getUser();
        
        // Logique de redirection basée sur le rôle :
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SPONSOR')) {
            // Afficher le Dashboard Global
            $template = 'dashboard/admin_global_dashboard.html.twig';
        } else {
            // Afficher le Dashboard Personnel (Mes Tâches)
            $template = 'dashboard/member_personal_dashboard.html.twig';
        }

        return $this->render($template, [
            'user' => $user,
            // ... autres données de dashboard ...
        ]);
    }
}