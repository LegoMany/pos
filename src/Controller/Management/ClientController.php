<?php

namespace Pos\Controller\Management;

use Pos\Entity\Client;
use Pos\Form\ClientType;
use Pos\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends AbstractController
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function list(): Response
    {
        return $this->render('management/client/list.html.twig', [
            'clients' => $this->clientRepository->findAll(),
        ]);
    }

    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('management_client_list');
        }

        return $this->render('management/client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('management_client_list');
        }

        return $this->render('management/client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Client $client): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($client);
        $entityManager->flush();

        return $this->redirectToRoute('management_client_list');
    }
}