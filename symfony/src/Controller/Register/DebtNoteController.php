<?php

namespace Pos\Controller\Register;

use Pos\Domain\Register;
use Pos\Entity\Item;
use Pos\Entity\Product;
use Pos\Entity\Sale;
use Pos\Form\DebtNoteType;
use Pos\Repository\CategoryRepository;
use Pos\Repository\ItemRepository;
use Pos\Repository\SaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DebtNoteController extends AbstractController
{
    private SaleRepository $saleRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(SaleRepository $saleRepository, CategoryRepository $categoryRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function list(): Response
    {
        return $this->render('register/debtnotes/list.html.twig', [
            'notes' => $this->saleRepository->findDebtNotes(),
        ]);
    }

    public function new(Request $request): Response
    {
        $note = new Sale();
        $form = $this->createForm(DebtNoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('register_debtnote_show', [
                'note' => $note->id,
            ]);
        }

        return $this->render('register/debtnotes/new.html.twig', [
            'note' => $note,
            'form' => $form->createView(),
        ]);
    }

    public function show(Request $request, Sale $note): Response
    {
        return $this->render('register/debtnotes/show.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
            'note' => $note,
        ]);
    }

    public function addProduct(Sale $note, Product $product): Response
    {
        $note->addProduct($product);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('register_debtnote_show', [
            'note' => $note->id,
        ]);
    }

    public function removeItem(Sale $note, Item $item, ItemRepository $itemRepository): Response
    {
        $itemRepository->delete($item);

        return $this->redirectToRoute('register_debtnote_show', [
            'note' => $note->id,
        ]);
    }

    public function close(Sale $note, Register $register): Response
    {
        $register->closeSale($note);
        return $this->redirectToRoute('register_debtnote_list');
    }

    public function delete(Sale $note): Response
    {
        if ($note->items->count() > 0) {
            $this->addFlash('error', 'Kann nicht gelöscht werden, da es noch Artikel zu dieser Notiz gibt.');
            return $this->redirectToRoute('register_debtnote_list');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $note->client = null;
        $entityManager->remove($note);
        $entityManager->flush();

        return $this->redirectToRoute('register_debtnote_list');
    }
}