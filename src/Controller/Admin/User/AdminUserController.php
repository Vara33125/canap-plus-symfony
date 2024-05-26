<?php
namespace App\Controller\Admin\User;



use App\Entity\User;

use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminUserController extends AbstractController
{
    #[Route('/user', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('pages/admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    #[Route('/user/{id<\d+>}/roles', name: 'admin_user_role', methods: ['POST'])]
    public function publish(User $user, Request $request, EntityManagerInterface $em): Response
    {
        //permet de vérifier si le jeton est valide
        if($this->isCsrfTokenValid("adminUser_user_{$user->getId()}", $request->request->get('_csrf_token') ))
        {
        $roles = $user->getRoles();
        // Vérifie si l'utilsateur a le role ADMIN
        if (!in_array('ROLE_ADMIN', $roles) ) 
        {
            // AJOUTER LE ROLE ADMIN
                $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
                                        
            // Demander au manager des entités de préparer la requête de modification
                $em->persist($user);
            // Générer le message flash 
                $this->addFlash("success", "L'utilisateur {$user->getPrenom()} {$user->getNom()} a été ajouté à la liste des administrateurs");

                
        }
        elseif (in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_SUPER_ADMIN', $roles) )
        {
                $user->setRoles(['ROLE_USER']);
                            
            // Demander au manager des entités de préparer la requête de modification
                $em->persist($user);
            // Générer le message flash
                $this->addFlash("success", "L'utilisateur {$user->getPrenom()} {$user->getNom()} a été retiré de la liste des administrateurs");
        }
        // Demander au manager des entités d'exécuter la requête préparée
        elseif (in_array('ROLE_SUPER_ADMIN', $roles) ) 
        {
            $this->addFlash("error", "Le SUPER ADMIN ne peux pas être retiré de la liste des administrateurs");
            return $this->redirectToRoute('app_admin_user_index');
        }
        $em->flush();
    }       
        return $this->redirectToRoute('app_admin_user_index');
}

#[Route('/user/{id}/delete', name: 'app_product_delete', methods: ['POST'])]
public function delete(Request $request, User $user, EntityManagerInterface $em): Response
{
    if ($this->isCsrfTokenValid("delete_user_{$user->getId()}", $request->request->get('_csrf_token') )) {
        
        $roles = $user->getRoles();
        // Vérifie si l'utilsateur a le role SUPER ADMIN
        if (in_array('ROLE_SUPER_ADMIN', $roles) )
        {
            $this->addFlash("error", "Le SUPER ADMIN ne peux pas être supprimé avec succès");
            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }
        else
        {
            $em->remove($user);
            $em->flush();
            $this->addFlash("success", "L'utilisateur {$user->getPrenom()} {$user->getNom()} a été supprimé");
        }
    }

    return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
}
}
