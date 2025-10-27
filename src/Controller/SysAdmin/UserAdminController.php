<?php
namespace App\Controller\SysAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use App\Repository\UserRepository;
use App\Entity\User; // Entité à créer
use App\User\Form\UserCreationFormType; // Le Formulaire à créer
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface; // Pour sauvegarder

use Symfony\Component\HttpFoundation\Request; // Pour gérer la soumission

use Symfony\Component\Security\Http\Attribute\IsGranted;
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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
         // 1. Créer une nouvelle instance de l'Entité à mapper
        $user = new User(); 
        
        // 2. Créer le Formulaire, en le liant à l'Entité et à la Classe de Formulaire
        // NOTE: Il faut d'abord créer la classe UserCreationFormType
        $form = $this->createForm(UserCreationFormType::class, $user);
        
        // 3. Demander au Formulaire de gérer la soumission de la requête (GET/POST)
        $form->handleRequest($request);

        // 4. Logique de Traitement POST (Soumission)
        if ($form->isSubmitted() && $form->isValid()) {
            // Ici, vous allez ajouter la logique critique de sécurité :
            //   - Hachage du mot de passe (puisque password est 'mapped => false')
            //   - Attribution des rôles (puisque 'roles' est 'multiple => true')
            
            // Pour l'instant, juste pour tester la redirection :
            // $entityManager->persist($user);
            // $entityManager->flush();

            $this->addFlash('success', 'Le compte utilisateur a été créé avec succès.');
            return $this->redirectToRoute('sysadmin_user_list');
        }

        // 5. Rendre le Template (Pour le GET ou si POST échoue)
        return $this->render('sysadmin/users/add_user.html.twig', [
            // C'est cette ligne qui passe la variable 'form' à Twig
            'creationForm' => $form->createView(), 
            'users' => [], // Conserver pour éviter d'autres erreurs Twig si nécessaire
        ]);
    }
}
