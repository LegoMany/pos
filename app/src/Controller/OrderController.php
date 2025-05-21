<?php

declare(strict_types=1);

namespace Pos\Controller;

use Pos\Entity\Transaction;
use Pos\Form\OrderTransactionType;
use Pos\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/order/', name: 'order_')]
class OrderController extends AbstractController
{
    protected TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }
    
    #[Route('new', name: 'new')]
    public function new(Request $request): Response
    {
        $order = new Transaction();
        $form = $this->createForm(OrderTransactionType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('pos_index');
        }

        return $this->render('order/new.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('{order}/show', name: 'show', requirements: ['order' => '\d+'])]
    public function show(Transaction $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }
    
    #[Route('{order}/edit', name: 'edit', requirements: ['order' => '\d+'])]
    public function edit(Request $request, Transaction $order): Response
    {
        $form = $this->createForm(OrderTransactionType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pos_index');
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('{order}/delete', name: 'delete', requirements: ['order' => '\d+'])]
    public function delete(Request $request, Transaction $order): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($order);
        $entityManager->flush();

        return $this->redirectToRoute('pos_index');
    }
}