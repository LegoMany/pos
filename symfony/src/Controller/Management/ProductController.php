<?php

namespace Pos\Controller\Management;

use Doctrine\ORM\EntityManagerInterface;
use Pos\Entity\Product;
use Pos\Form\ProductType;
use Pos\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    public function list(): Response
    {
        return $this->render('management/product/list.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('management_product_list');
        }

        return $this->render('management/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('management_product_list');
        }

        return $this->render('management/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Product $product): Response
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->redirectToRoute('management_product_list');
    }
}