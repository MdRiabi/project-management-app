<?php
// src/Controller/AccountController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/request-account', name: 'request_account', methods: ['GET'])]
    public function requestAccount(): Response
    {
        return $this->render('home/request_account.html.twig');
    }
}
