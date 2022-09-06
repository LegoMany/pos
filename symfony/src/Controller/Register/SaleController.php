<?php

namespace Pos\Controller\Register;

use Doctrine\ORM\EntityManagerInterface;
use Pos\Domain\Register;
use Pos\Entity\Item;
use Pos\Entity\Product;
use Pos\Entity\Sale;
use Pos\Repository\CategoryRepository;
use Pos\Repository\ItemRepository;
use Pos\Repository\SaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    public function new(): Response
    {
        $sale = new Sale();
        $this->entityManager->persist($sale);
        $this->entityManager->flush();

        return $this->redirectToRoute('register_sale_show', [
            'sale' => $sale->id,
        ]);
    }

    public function show(Sale $sale): Response
    {
        return $this->render('register/sale/show.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
            'sale' => $sale,
        ]);
    }

    public function addProduct(Sale $sale, Product $product): Response
    {
        $sale->addProduct($product);
        $this->entityManager->flush();
        return $this->redirectToRoute('register_sale_show', [
            'sale' => $sale->id,
        ]);
    }

    public function removeItem(Sale $sale, Item $item, ItemRepository $itemRepository): Response
    {
        $itemRepository->delete($item);
        return $this->redirectToRoute('register_sale_show', [
            'sale' => $sale->id,
        ]);
    }

    public function back(Sale $sale, SaleRepository $saleRepository, ItemRepository $itemRepository): Response
    {
        foreach ($sale->items as $item) {
            $itemRepository->delete($item);
        }
        $saleRepository->delete($sale);
        return $this->redirectToRoute('home');
    }

    public function close(Sale $sale, Register $register): Response
    {
        $register->closeSale($sale);
        return $this->redirectToRoute('register');
    }
}