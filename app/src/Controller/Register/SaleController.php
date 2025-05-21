<?php

namespace Pos\Controller\Register;

use Pos\Domain\Register;
use Pos\Entity\Item;
use Pos\Entity\Product;
use Pos\Entity\Sale;
use Pos\Repository\CategoryRepository;
use Pos\Repository\ItemRepository;
use Pos\Repository\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register/', name: 'register_sale_')]
class SaleController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/register/new', name: 'new')]
    public function new(): Response
    {
        $sale = new Sale();
        $this->entityManager->persist($sale);
        $this->entityManager->flush();

        return $this->redirectToRoute('register_sale_show', [
            'sale' => $sale->id,
        ]);
    }

    #[Route('/register/{sale}', name: 'show', requirements: ['sale' => '\d+'])]
    public function show(Sale $sale): Response
    {
        return $this->render('register/sale/show.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
            'sale' => $sale,
        ]);
    }

    #[Route('/register/sale/{sale}/add/{product}', name: 'addproduct', requirements: ['sale' => '\d+', 'product' => '\d+'])]
    public function addProduct(Sale $sale, Product $product): Response
    {
        $sale->addProduct($product);
        $this->entityManager->flush();
        return $this->redirectToRoute('register_sale_show', [
            'sale' => $sale->id,
        ]);
    }

    #[Route('/register/sale/{sale}/remove/{item}', name: 'removeitem', requirements: ['sale' => '\d+', 'item' => '\d+'])]
    public function removeItem(Sale $sale, Item $item, ItemRepository $itemRepository): Response
    {
        $itemRepository->delete($item);
        return $this->redirectToRoute('register_sale_show', [
            'sale' => $sale->id,
        ]);
    }

    #[Route('/register/{sale}/back', name: 'back', requirements: ['sale' => '\d+'])]
    public function back(Sale $sale, SaleRepository $saleRepository, ItemRepository $itemRepository): Response
    {
        foreach ($sale->items as $item) {
            $itemRepository->delete($item);
        }
        $saleRepository->delete($sale);
        return $this->redirectToRoute('home');
    }

    #[Route('/register/sale/{sale}/close', name: 'close', requirements: ['sale' => '\d+'])]
    public function close(Sale $sale, Register $register): Response
    {
        $register->closeSale($sale);
        return $this->redirectToRoute('register');
    }
}