<?php

namespace App\Controller\Admin\Product;



use DateTimeImmutable;
use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('pages/admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'app_product_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/product/{id<\d+>}/publish', name: 'admin_product_publish', methods: ['POST'])]
    public function publish(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        //permet de vérifier si le jeton est valide
        if($this->isCsrfTokenValid("publish_product_{$product->getId()}", $request->request->get('_csrf_token') ))
        {
        // Si l'article n'a pas encore été publié
        if ( false === $product->isPublished() ) 
        {
            // Publier l'article
                $product->setPublished(true);
            // Mettre à jour sa date de publication
                $product->setPublishedAt(new DateTimeImmutable());
                          
            // Demander au manager des entités de préparer la requête de modification
                $em->persist($product);
            // Générer le message flash expliquant que l'article a été publié
                $this->addFlash("success", "L'article {$product->getName()} a été publié avec succès");

                
        }
        else
        {
            // Retirer l'article de la liste des publications
                $product->setPublished(false);
            // Mettre à nul la date de publication
                $product->setPublishedAt(null);
                            
            // Demander au manager des entités de préparer la requête de modification
                $em->persist($product);
            // Générer le message expliquant de l'article a été retiré de la liste des publications
                $this->addFlash("success", "L'article {$product->getName()} a été retiré des publications");
        }
        // Demander au manager des entités d'exécuter la requête préparée
        $em->flush();
        // Effectuer une redirection vers la page listant les articles 
            // Puis, arrêter l'exécution du script
    }       
        return $this->redirectToRoute('app_product_index');
    }

    #[Route('/product/{id<\d+>}/{slug}/show', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('pages/admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/{id<\d+>}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductFormType::class, $product);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid("delete_product_{$product->getId()}", $request->request->get('_csrf_token') )) {
            $em->remove($product);
            $em->flush();
            $this->addFlash("success", "L'article {$product->getName()} a été supprimé avec succès");
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
