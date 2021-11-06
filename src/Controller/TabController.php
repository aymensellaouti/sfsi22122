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
    public function index($nb = 5): Response
    {
        $tab = [];
        for($i = 0; $i < $nb; $i++) {
            $tab[] = rand(0, 20);
        }
        return $this->render('tab/index.html.twig', [
            'tableau' => $tab,
        ]);
    }

    #[Route('/users', name: 'users.tab')]
    function showUsers() {
        $users = [
            ['name' => 'name1', 'firstname' => 'fn1', 'age' => 40],
            ['name' => 'name2', 'firstname' => 'fn2', 'age' => 40],
            ['name' => 'name3', 'firstname' => 'fn3', 'age' => 40],
        ];
        return $this->render('tab/users.html.twig', [
            'users' => $users
        ]);
    }
}
