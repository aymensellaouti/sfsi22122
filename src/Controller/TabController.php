<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tab")
 */
class TabController extends AbstractController
{
    #[Route('/show/{nb<\d+>?5}', name: 'tab')]
    public function index($nb): Response
    {
        $tab = [];
        for($i = 0; $i < $nb; $i++) {
            $tab[] = rand(0, 20);
        }
        return $this->render('tab/index.html.twig', [
            'tableau' => $tab,
        ]);
    }
}
