<?php

namespace Pos\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    public function main(): Response
    {
        return $this->render('dashboard.html.twig');
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