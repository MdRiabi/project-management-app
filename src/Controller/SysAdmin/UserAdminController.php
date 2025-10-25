<?php
namespace App\Controller\SysAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use App\Repository\UserRepository;

#[Route('/sysadmin', name: 'sysadmin_')]
class UserAdminController extends AbstractController
{
    #[Route('/users', name: 'user_list', methods: ['GET'])]
    public function list(/* UserRepository $repo */): Response
    {
        // Example placeholder data until repository wiring
        $users = [];
        // If using a repository: $users = $repo->findAll();

        return $this->render('sysadmin/users/list_users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/new', name: 'user_new', methods: ['GET','POST'])]
    public function new(): Response
    {
        // Placeholder route for "Add new user" button
        return $this->render('sysadmin/users/list_users.html.twig', [
            'users' => [],
        ]);
    }
}
