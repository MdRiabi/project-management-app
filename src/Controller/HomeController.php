<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Statistics with default values
        $stats = [
            'tasksCreated' => 15420,
            'tasksCompleted' => 12856,
            'activeUsers' => 3240,
        ];

        return $this->render('home/index.html.twig', [
            'stats' => $stats,
        ]);

    }
    #[Route('/request-account', name: 'request_account')]
    public function requestAccount(): Response
    {
        return $this->render('home/request_account.html.twig');
    }
}
