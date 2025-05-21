<?php

declare(strict_types=1);

namespace Pos\Controller;

use Pos\Repository\SaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function main(SaleRepository $saleRepository): Response
    {
        return $this->render('home.html.twig', [
            'notes' => $saleRepository->findDebtNotes(),
        ]);
    }

    #[Route('/management', name: 'management')]
    public function management(): Response
    {
        return $this->render('management/show.html.twig');
    }
}