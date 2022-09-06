<?php

namespace Pos\Controller\Management;

use Doctrine\ORM\EntityManagerInterface;
use Pos\Entity\Category;
use Pos\Form\CategoryType;
use Pos\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="management_category_", path="/management/categories")
 */
class CategoryController extends AbstractController
{
    protected CategoryRepository $categoryRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(name="list", path="")
     */
    public function list(): Response
    {
        return $this->render('management/category/list.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route(name="new", path="/new")
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirectToRoute('management_category_list');
        }

        return $this->render('management/category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="edit", path="/{category}", requirements={"category"="\d+"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('management_category_list');
        }

        return $this->render('management/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="delete", path="/{category}/delete", requirements={"category"="\d+"})
     */
    public function delete(Category $category): Response
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return $this->redirectToRoute('management_category_list');
    }
}