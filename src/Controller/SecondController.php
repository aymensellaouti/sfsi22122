<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecondController extends AbstractController
{
    #[Route('/second/{name}', name: 'second')]
    public function index($name): Response
    {
        return $this->render('second/index.html.twig', [
            'controller_name' => 'SecondController',
            'name' => $name,
        ]);
    }
}
