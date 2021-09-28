<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController
{
    /**
     * @Route("/first")
     */
    function myFirstFunction() {
        $response = new Response('
            <html>
                <header>
                    <title>My first page</title>
                </header>
                <body>
                    <h1>Hello Si2</h1>  
                </body>
            </html>
        ');
        return $response;
    }

}