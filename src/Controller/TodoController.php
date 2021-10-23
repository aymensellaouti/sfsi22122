<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todo")
 */
class TodoController extends AbstractController
{
    #[Route('/', name: 'todo')]
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
    #[Route('/add/{name}/{content<\d+>}', name: 'todo.add')]
    public function addTodo(Request $request, $name, $content) {
        $session = $request->getSession();
        if ($session->has('mesTodos')) {
            $todos = $session->get('mesTodos');
            if (isset($todos[$name])) {
                $this->addFlash('error', " Le todo d'id $name existe déjà");
            } else {
                $todos[$name] = $content;
                $this->addFlash('success', " Le todo $name a été ajouté avec succès");
                $session->set('mesTodos', $todos);
            }
        } else {
            $this->addFlash('error', " Le tableau n'a pas encore été crée :(");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content) {
        $session = $request->getSession();
        if ($session->has('mesTodos')) {
            $todos = $session->get('mesTodos');
            if (!isset($todos[$name])) {
                $this->addFlash('error', " Le todo d'id $name n'existe pas");
            } else {
                $todos[$name] = $content;
                $this->addFlash('success', " Le todo $name a été modifié avec succès");
                $session->set('mesTodos', $todos);
            }
        } else {
            $this->addFlash('error', " Le tableau n'a pas encore été crée :(");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name, $content)
    {
        $session = $request->getSession();
        if ($session->has('mesTodos')) {
            $todos = $session->get('mesTodos');
            if (isset($todos[$name])) {
                $this->addFlash('error', " Le todo d'id $name existe déjà");
            } else {
                unset($todos[$name]);
                $this->addFlash('success', " Le todo $name a été supprimé avec succès");
                $session->set('mesTodos', $todos);
            }
        } else {
            $this->addFlash('error', " Le tableau n'a pas encore été crée :(");
        }
        return $this->redirectToRoute('todo');
    }
        #[Route('/reset', name: 'todo.reset')]
        public function resetTodo(Request $request) {
            $session = $request->getSession();
            if ($session->has('mesTodos')) {
                $session->remove('mesTodos');
            } else {
                $this->addFlash('error', " Le tableau n'a pas encore été crée :(");
            }
            return $this->redirectToRoute('todo');
        }
}
