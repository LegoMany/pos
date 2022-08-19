<?php

namespace Pos\Controller;

use Pos\Entity\Product;
use Pos\Form\ProductType;
use Pos\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $ProductRepository)
    {
        $this->productRepository = $ProductRepository;
    }

    public function list(): Response
    {
        return $this->render('register/product/list.html.twig', [
            'products' => $this->productRepository->findAll(),
        ]);
    }

    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('register_product_list');
        }

        return $this->render('register/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('register_product_list');
        }

        return $this->render('register/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Product $product): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('register_product_list');
    }
}