<?php

namespace App\Controller;

use App\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'personne')]
    public function index(): Response
    {
        return $this->render('personne/index.html.twig', [
            'controller_name' => 'PersonneController',
        ]);
    }

    #[Route('/add/{name}/{firstname}/{age}', name: 'personne.add')]
    public function addPersonne($name, $firstname, $age) {
        $manager = $this->getDoctrine()
                        ->getManager();
        $personne = new Personne();
        $personne->setAge($age);
        $personne->setName($name);
        $personne->setFirstname($firstname);
//        $personne2 = new Personne();
//        $personne2->setAge($age);
//        $personne2->setName($name);
//        $personne2->setFirstname($firstname);

        //Persister ajouter dans la transaction
        $manager->persist($personne);
//        $manager->persist($personne2);
        //executer la transaction
        $manager->flush();
        return $this->render('personne/personne-details.html.twig', [
            'personne' => $personne
        ]);
    }
    #[Route('/delete/{id}', name: 'personne.delete')]
    public function deletePersonne(Personne $personne = null) {
        if($personne) {
            // recupérer manager
            $manager = $this->getDoctrine()->getManager();
            // supprime le user avec le id
            $manager->remove($personne);
            $manager->flush();
            $this->addFlash('success', "La personne a été supprimé avec succès");
        } else {
            $this->addFlash('error', "Erreur veuillez vérifier votre requete");
        }
        return $this->redirectToRoute('personne.list');
    }
    #[Route('/details/{id}', name: 'personne.details')]
    public function getPersonneById(Personne $personne = null) {
        if (!$personne)  {
            $this->addFlash('error', "Erreur veuillez vérifier votre requete");
        }
        return $this->render('personne/personne-details.html.twig', [
            'personne' => $personne
        ]);
    }
    #[Route('/all/{numPage?1}/{limit?9}', name: 'personne.list')]
    public function listAllPersonnes($numPage, $limit) {
        // Récupérer la liste des personnes
        $repository = $this->getDoctrine()
                        ->getRepository(Personne::class);


//        $personnes = $repository->findBy(
//            [], [], $limit, $offset
//        );
        $personnes = $repository->findAll();
        $nbPersonnes = count($personnes);
        $nbrePages = ($nbPersonnes % $limit) ? ceil($nbPersonnes / $limit) : $nbPersonnes / $limit;
        if ($numPage > $nbrePages) {
            $numPage = $nbrePages;
        }
        $offset = ($numPage - 1) * $limit;
        $personnesToShow = array_slice($personnes, $offset, $limit);

        // l'envoyer à twig
        return $this->render('personne/list.html.twig', [
            'personnes' => $personnesToShow,
            'nbrePage' => $nbrePages,
            'page' => $numPage,
            'limit' => $limit
        ]);
    }
    #[Route('/all/age/{ageMin}/{ageMax}/{numPage?1}/{limit?9}', name: 'personne.list.age')]
    public function listAllPersonnesByAge($ageMin, $ageMax, $numPage, $limit) {
        // Récupérer la liste des personnes
        $repository = $this->getDoctrine()
            ->getRepository(Personne::class);
//        $personnes = $repository->findBy(
//            [], [], $limit, $offset
//        );

        $personnes = $repository->getPersonneByIntervalAge($ageMin, $ageMax);
        $nbPersonnes = count($personnes);
        $nbrePages = ($nbPersonnes % $limit) ? ceil($nbPersonnes / $limit) : $nbPersonnes / $limit;
        if ($numPage > $nbrePages) {
            $numPage = $nbrePages;
        }
        $offset = ($numPage - 1) * $limit;
        $personnesToShow = array_slice($personnes, $offset, $limit);

        // l'envoyer à twig
        return $this->render('personne/list.html.twig', [
            'personnes' => $personnesToShow,
            'nbrePage' => $nbrePages,
            'page' => $numPage,
            'limit' => $limit
        ]);
    }
}
