<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'todo')]
    public function index(
        Request $request
    ): Response
    {
        // récupérer la session
        $session = $request->getSession();
        // vérifier si la session est vide
            //si oui je la remplie avec la liste des todos
            if(!$session->has('mesTodos')) {
                $todos = [
                    'achat'=>'acheter clé usb',
                    'cours'=>'Finaliser mon cours',
                    'correction'=>'corriger mes examens'
                ];
                $session->set('mesTodos', $todos);
            }
        //appelle la page index.html.twig
        return $this->render('todo/index.html.twig');
    }
}
