<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Project;
use App\Entity\ProjectOwner;
use App\Form\CreateProjectType;
use App\Form\UpdateProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    /**
     * Allows the user to have a look at all the projects s/he created
     * 
     * @Route("/mes_projets", name="project_ownProjects")
     * @return Response
     */
    public function ownProjects(): Response
    {
        $userId = $this->getUser()->getId();
        $projectRepo = $this->getDoctrine()->getRepository(Project::class);
        $projects = $projectRepo->getOwnProjects($userId);


        return $this->render('project/ownProjects.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * Allow a user to create a project and publish it on the website
     * 
     * @Route("/creer_project", name="project_create")
     */
    public function create(Request $req, EntityManagerInterface $emi): Response
    {
        $user = $this->getUser()->getId();
        $project = new Project();
        $projectOwner = new ProjectOwner();

        $form = $this->createForm(CreateProjectType::class);
        $form->handleRequest($req);

        dump($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectOwner->setUserId($user);
            $project->setCreateDate(new DateTime("now", new DateTimeZone("Europe/Paris")));
            $project->setActive(true);
            $project->setOwnerId($projectOwner);

            $emi->persist($projectOwner);
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
     * Allows users to get / read more information about ones project
     * 
     * @Route("/details_projet/{id}", name="project_details", requirements={"id"="\d+"})
     */
    public function details($id): Response
    {
        $projectRepo = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $id]);

        return $this->render('project/details.html.twig', [
            'project' => $project
        ]);
    }

    /**
     * Allows the user to modify and update his project
     *
     * @Route("/modifier_projet/{id}",  name="project_update", requirements={"id"="\d+"})
     * @param [type] $id
     * @param Request $req
     * @param EntityManagerInterface $emi
     * @return void
     */
    public function update($id, Request $req, EntityManagerInterface $emi): Response
    {
        $user = $this->getUser();

        $projectRepo = $this->getDoctrine()->getRepository(Project::class);
        $projectToUpdate = $projectRepo->find($id);

        $form = $this->createForm(UpdateProjectType::class, $projectToUpdate);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $date = new DateTime("now", new DateTimeZone("Europe/Paris"));
            $project->setModifyDate($date);
            $emi->persist($project);
            $emi->flush();

            $this->addFlash('success', 'La modification de votre projet a bien été enregistrée !');

            return $this->redirectToRoute('project_ownProjects');
        }

        return $this->render('project/update.html.twig', [
            'projectToUpdate' => $projectToUpdate,
            'form' => $form->createView()
        ]);
    }
}
