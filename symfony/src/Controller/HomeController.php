<?php

namespace Pos\Controller;

use Pos\Repository\SaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function main(SaleRepository $saleRepository): Response
    {
        return $this->render('home.html.twig', [
            'notes' => $saleRepository->findDebtNotes(),
        ]);
    }

    public function management(): Response
    {
        return $this->render('management/show.html.twig');
    }

    public function register(): Response
    {
        return $this->render('register/show.html.twig');
    }
}