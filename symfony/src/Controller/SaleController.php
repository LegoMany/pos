<?php

namespace Pos\Controller;

use Pos\Entity\Transaction;
use Pos\Form\SaleTransactionType;
use Pos\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends AbstractController
{
    protected TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

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

    public function delete(Transaction $sale): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sale);
        $entityManager->flush();

        return $this->redirectToRoute('transactions_list');
    }
}