<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    #[
        Route('/edit/{id?0}', name: 'personne.edit')
    ]
    public function addFormPersonne(Request $request, Personne $personne = null, SluggerInterface $slugger) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$personne) {
            $personne = new Personne();
        }
        $form = $this->createForm(PersonneType::class,$personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //Gestion de l'uplaod
            $imageFile = $form->get('image')->getData();
            // si j upload un file
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('personnes_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    dd($e);
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $personne->setImage($newFilename);
            }

            $manager = $this->getDoctrine()
                        ->getManager();
            $manager->persist($personne);
            $manager->flush();
        $this->addFlash('success',"La personne ".$personne->getName()." a été ajouté avec succès");
        return $this->redirectToRoute('personne.list.alls');
        }
        return $this->render('personne/add-personne.html.twig', [
            'form' => $form->createView()
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
    #[Route('/alls', name: 'personne.list.alls')]
    public function listAllsPersonnes() {
        // Récupérer la liste des personnes
        $repository = $this->getDoctrine()
            ->getRepository(Personne::class);
        $personnes = $repository->findAll();
        // l'envoyer à twig
        return $this->render('personne/listdt.html.twig', [
            'personnes' => $personnes,
        ]);
    }
    #[Route('/stats/age/{ageMin}/{ageMax}', name: 'personne.stats.age')]
    public function statsPersonnesByAge($ageMin, $ageMax) {
        // Récupérer la liste des personnes
        $repository = $this->getDoctrine()
            ->getRepository(Personne::class);
        $stats = $repository->getStatsPersonneByIntervalAge($ageMin, $ageMax);
        return $this->render('personne/statsAge.html.twig', [
            'stat' => $stats[0],
            'ageMin' => $ageMin,
            'ageMax' => $ageMax,
        ]);
    }

}

