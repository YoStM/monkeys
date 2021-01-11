<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function index(): Response
    {
        $projectRepo = $this->getDoctrine()->getRepository(Project::class);
        $newProjects = $projectRepo->findNewProjects();
        $oldProjects = $projectRepo->findOldProjects();
        $projects = $projectRepo->findAll(['active' => true]);

        return $this->render('main/index.html.twig', [
            'projects' => $projects,
            'newProjects' => $newProjects,
            'oldProjects' => $oldProjects
        ]);
    }
}
