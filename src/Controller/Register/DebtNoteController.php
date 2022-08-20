<?php

namespace Pos\Controller\Register;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Pos\Domain\Register;
use Pos\Entity\DebtNote;
use Pos\Entity\Item;
use Pos\Entity\Product;
use Pos\Form\DebtNoteType;
use Pos\Repository\CategoryRepository;
use Pos\Repository\DebtNoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DebtNoteController extends AbstractController
{
    private DebtNoteRepository $debtNoteRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(DebtNoteRepository $debtNoteRepository, CategoryRepository $categoryRepository)
    {
        $this->debtNoteRepository = $debtNoteRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function list(): Response
    {
        return $this->render('register/debtnotes/list.html.twig', [
            'notes' => $this->debtNoteRepository->findAll(),
        ]);
    }

    public function new(Request $request): Response
    {
        $note = new DebtNote();
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

    public function show(Request $request, DebtNote $note): Response
    {
        return $this->render('register/debtnotes/show.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
            'note' => $note,
        ]);
    }

    public function addProduct(DebtNote $note, Product $product): Response
    {
        $note->addProduct($product);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('register_debtnote_show', [
            'note' => $note->id,
        ]);
    }

    public function removeProduct(DebtNote $note, Product $product): Response
    {
        $item = $note->items->filter(function (Item $item) use ($product) {
            return $item->product->id === $product->id;
        })->first();

        $note->removeProduct($product);


        $this->getDoctrine()->getManager()->remove($item);
        $this->getDoctrine()->getManager()->persist($note);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('register_debtnote_show', [
            'note' => $note->id,
        ]);
    }

    public function close(DebtNote $note, Register $register): Response
    {
        $register->closeDebtNote($note);
        return $this->redirectToRoute('register_debtnote_list');
    }

    public function delete(DebtNote $note): Response
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($note);
            $entityManager->flush();
        } catch (ForeignKeyConstraintViolationException $exception) {
            $this->addFlash('error', 'Kann nicht gelöscht werden, da es noch Artikel zu dieser Notiz gibt.');
        }

        return $this->redirectToRoute('register_debtnote_list');
    }
}