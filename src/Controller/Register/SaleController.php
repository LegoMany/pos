<?php

namespace Pos\Controller\Register;

use Pos\Domain\Register;
use Pos\Entity\Item;
use Pos\Entity\Product;
use Pos\Entity\Sale;
use Pos\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends AbstractController
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function new(): Response
    {
        $sale = new Sale();
        $this->getDoctrine()->getManager()->persist($sale);
        $this->getDoctrine()->getManager()->flush();

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
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('register_sale_show', [
            'sale' => $sale->id,
        ]);
    }

    public function removeProduct(Sale $sale, Product $product): Response
    {
        $item = $sale->items->filter(function (Item $item) use ($product) {
            return $item->product->id === $product->id;
        })->first();

        $sale->removeProduct($product);


        $this->getDoctrine()->getManager()->remove($item);
        $this->getDoctrine()->getManager()->persist($sale);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('register_sale_show', [
            'sale' => $sale->id,
        ]);
    }

    public function back(Sale $sale): Response
    {
        foreach ($sale->items as $item) {
            $this->getDoctrine()->getManager()->remove($item);
        }

        $this->getDoctrine()->getManager()->remove($sale);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('register');
    }

    public function close(Sale $sale, Register $register): Response
    {
        $register->closeSale($sale);
        return $this->redirectToRoute('register');
    }
}