<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwigExamplesController extends AbstractController
{
    #[Route('/twig/examples', name: 'twig_examples')]
    public function index(): Response
    {
        return $this->render('twig_examples/index.html.twig');
    }
    #[Route('/twig/examples/fille', name: 'twig_examples_fille')]
    public function fille(): Response
    {
        return $this->render('twig_examples/fille.html.twig');
    }
}
