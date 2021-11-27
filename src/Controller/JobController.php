<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/job')]
class JobController extends AbstractController
{
    #[Route('/list', name: 'job.list')]
    public function index(): Response
    {
        /*
         * 1- Récupérer le Repository
         * 2- Utiliser findall pour récupérer tous les jobs
         * 3- Envoyer la liste vers notre Twig
         * */
        //1
        $repository = $this->getDoctrine()->getRepository(Job::class);
        //2
        $jobs = $repository->findAll();
        //3
        return $this->render('job/index.html.twig', [
            'jobs' => $jobs,
        ]);
    }
    #[Route('/edit/{id?0}', name: 'job.edit')]
    public function edit(Request $request, Job $job = null): Response
    {
        if (!$job) {
            $job = new Job();
        }
        // Crée le formulaire
        $form = $this->createForm(JobType::class, $job);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        // Associe la requete a notre formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Ajouter ou mettre à jour le job dans la base de données
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($job);
            $manager->flush();
            // rediriger vers la liste des jobs
            return $this->redirectToRoute('job.list');
        } else {
            return $this->render('job/addJob.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }
}
