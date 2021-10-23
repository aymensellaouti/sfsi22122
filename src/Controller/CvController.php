<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CvController extends AbstractController
{
    #[Route("cv/{name}/{firstname}/{age<\d{1,2}>}/{section<GL|RT>}", name: 'cv.home')]
    public function cv(Request $request, $name, $firstname, $age, $section) {
        $session = $request->getSession();
        return $this->render('cv/cv.html.twig', [
            'name' => $name,
            'firstname' => $firstname,
            'age' => $age,
            'section' => $section,
        ]);
    }
}
