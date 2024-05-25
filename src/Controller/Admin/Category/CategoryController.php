<?php

namespace App\Controller\Admin\Category;


use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class CategoryController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private CategoryRepository $categoryRepository
    )
    {
    }

    #[Route('/category', name: 'app_admin_category', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/category/index.html.twig', [
            "categories" => $this->categoryRepository->findAll()
        ]);
    }


    #[Route('/category/create', name: 'app_admin_category_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            

            $this->em->persist($category);

            $this->em->flush();

            $this->addFlash('success', "La catégorie a été ajoutée avec succès.");

            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('pages/admin/category/create.html.twig', [
            "form" => $form->createView()
        ]);
    }


    #[Route('/category/{id<\d+>}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
          

            $this->em->persist($category);

            $this->em->flush();

            $this->addFlash('success', "La catégorie {$category->getName()} a été modifié avec succès.");

            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('pages/admin/category/edit.html.twig', [
            "form" => $form->createView(),
            "category" => $category
        ]);
    }


    #[Route('/category/{id<\d+>}/delete', name: 'app_admin_category_delete', methods: ['GET', 'POST'])]
    public function delete(Category $category, Request $request): Response
    {
        if ( $this->isCsrfTokenValid('delete_category_'.$category->getId(), $request->request->get('_csrf_token')) )
        {
            $this->addFlash('success', "La catégorie {$category->getName()} a été supprimée avec succès.");

            $this->em->remove($category);

            $this->em->flush();
            
        }
        
        return $this->redirectToRoute('app_admin_category');

    }
}
