<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    /**
     * @Route("/first", name="home")
     */
    function myFirstFunction() {
        $response = new Response('
            <html>
                <header>
                    <title>My first page</title>
                </header>
                <body>
                    <h1>Hello Si2</h1>  
                    <p>cc</p>
                </body>
            </html>
        ');
        return $response;
    }

    #[Route("session", name: "session.nb.visite")]
    public function session(Request $request) {
        //Je récupére ma session
        $session = $request->getSession();
        //Je vérifie si j'ai la variable nbVisite
            // Si oui
            if ($session->has('nbVisite')) {
                // incrementer
                $nbVisite = $session->get('nbVisite');
                $nbVisite++;
                $session->set('nbVisite', $nbVisite);
            // si non
            } else {
                // on crée la variable et on l initialise à 1
                $nbVisite = 1;
                // on la met dans la session
                $session->set('nbVisite', $nbVisite);
                // on crée le flashBag
                $this->addFlash('info', "Bienvenu dans notre plateforme c'est votre première visite");
            }
        // on renvoi la twig
        return $this->render('session/index.html.twig');
    }

}