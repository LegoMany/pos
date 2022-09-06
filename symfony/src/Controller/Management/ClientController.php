<?php

namespace Pos\Controller\Management;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Pos\Entity\Client;
use Pos\Form\ClientType;
use Pos\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="management_client_", path="/management/clients")
 */
class ClientController extends AbstractController
{
    private ClientRepository $clientRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ClientRepository $clientRepository, EntityManagerInterface $entityManager)
    {
        $this->clientRepository = $clientRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(name="list", path="")
     */
    public function list(): Response
    {
        return $this->render('management/client/list.html.twig', [
            'clients' => $this->clientRepository->findAll(),
        ]);
    }

    /**
     * @Route(name="new", path="/new")
     */
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            return $this->redirectToRoute('management_client_list');
        }

        return $this->render('management/client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="edit", path="/{client}", requirements={"client"="\d+"})
     */
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('management_client_list');
        }

        return $this->render('management/client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="delete", path="/{client}/delete", requirements={"client"="\d+"})
     */
    public function delete(Client $client): Response
    {
        try {
            $this->entityManager->remove($client);
            $this->entityManager->flush();
        } catch (ForeignKeyConstraintViolationException $exception) {
            $this->addFlash('error', 'Kann nicht gelöscht werden, da es noch Einträge zu diesem Kunden gibt.');
        }

        return $this->redirectToRoute('management_client_list');
    }
}