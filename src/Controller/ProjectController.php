<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\CreateProjectType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/creer_project", name="project_create")
     */
    public function create(Request $req, EntityManagerInterface $emi): Response
    {
        $user = $this->getUser()->getId();
        $project = new Project();

        $form = $this->createForm(CreateProjectType::class);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setCreateDate(new DateTime());
            $project->setActive(true);
            $project->setCategoryId($req->request->get('category'));
            $project->setUserId($user);

            $emi->persist($project);
            $emi->flush();

            $this->addFlash('success', 'Votre projet est enregistré et maintenant diffusé auprès de nos meilleurs freelance');

            $this->redirectToRoute('main_home');
        }

        return $this->render('project/create.html.twig', [
            'projectForm' => $form->createView()
        ]);
    }

    /**
     * 
     * @Route("/projet/{id}", name="project_details")
     */
    public function details($id): Response
    {
        $projectRepo = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $id]);

        return $this->render('project/details.html.twig', [
            'project' => $project
        ]);
    }
}
