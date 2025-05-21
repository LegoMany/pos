<?php

declare(strict_types=1);

namespace Pos\Controller;

use Pos\Entity\Transaction;
use Pos\Form\SaleTransactionType;
use Pos\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sale/', name: 'sale_')]
class SaleController extends AbstractController
{
    protected TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    #[Route('new', name: 'new')]
    public function new(Request $request): Response
    {
        $sale = new Transaction();
        $form = $this->createForm(SaleTransactionType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sale);
            $entityManager->flush();

            return $this->redirectToRoute('transactions_list');
        }

        return $this->render('sale/new.html.twig', [
            'sale' => $sale,
            'form' => $form->createView(),
        ]);
    }

    #[Route('{sale}/edit', name: 'edit')]
    public function edit(Request $request, Transaction $sale): Response
    {
        $form = $this->createForm(SaleTransactionType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transactions_list');
        }

        return $this->render('sale/edit.html.twig', [
            'sale' => $sale,
            'form' => $form->createView(),
        ]);
    }

    #[Route('{sale}/delete', name: 'delete')]
    public function delete(Transaction $sale): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sale);
        $entityManager->flush();

        return $this->redirectToRoute('transactions_list');
    }
}