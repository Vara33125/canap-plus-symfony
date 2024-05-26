<?php
namespace App\Controller\Admin\Store;



use App\Entity\Store;

use App\Form\StoreFormType;
use App\Repository\StoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminStoreController extends AbstractController
{
    #[Route('/store', name: 'app_admin_store_index', methods: ['GET'])]
    public function index(StoreRepository $storeRepository): Response
    {
        return $this->render('pages/admin/store/index.html.twig', [
            'stores' => $storeRepository->findAll(),
        ]);
    }

    #[Route('/store/create', name: 'app_admin_store_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $store = new Store();
        $form = $this->createForm(StoreFormType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($store);
            $em->flush();

            return $this->redirectToRoute('app_admin_store_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/store/new.html.twig', [
            'store' => $store,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id}/edit', name: 'app_admin_store_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Store $store, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(StoreFormType::class, $store);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($store);
            $em->flush();

            return $this->redirectToRoute('app_admin_store_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/store/edit.html.twig', [
            'store' => $store,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_store_delete', methods: ['POST'])]
    public function delete(Request $request, Store $store, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid("delete_store_{$store->getId()}", $request->request->get('_csrf_token') )) {
            $em->remove($store);
            $em->flush();
        }

        return $this->redirectToRoute('app_admin_store_index', [], Response::HTTP_SEE_OTHER);
    }
}
