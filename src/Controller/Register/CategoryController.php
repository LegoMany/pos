<?php

namespace Pos\Controller\Register;

use Pos\Entity\Category;
use Pos\Entity\Product;
use Pos\Form\CategoryType;
use Pos\Form\ProductType;
use Pos\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function list(): Response
    {
        return $this->render('register/category/list.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('register_product_category_list');
        }

        return $this->render('register/category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('register_product_category_list');
        }

        return $this->render('register/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Category $category): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('register_product_category_list');
    }
}