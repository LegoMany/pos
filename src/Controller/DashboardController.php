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
}