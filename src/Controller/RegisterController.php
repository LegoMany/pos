<?php

namespace Pos\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends AbstractController
{
    public function show(): Response
    {
        return $this->render('management/show.html.twig');
    }
}