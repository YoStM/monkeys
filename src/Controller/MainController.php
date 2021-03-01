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
     * 
     * This is the website's home page
     */
    public function index(): Response
    {

        $projectRepo = $this->getDoctrine()->getRepository(Project::class);
        $newProjects = $projectRepo->findNewProjects();
        $oldProjects = $projectRepo->findOldProjects();
        $countActiveProjects = $projectRepo->countActiveProjects();
        $projects = $projectRepo->findAll(['active' => true]);

        dump($oldProjects);
        dump($countActiveProjects);

        return $this->render('main/index.html.twig', [
            'projects' => $projects,
            'newProjects' => $newProjects,
            'oldProjects' => $oldProjects,
            'countActiveProjects' => $countActiveProjects
        ]);
    }
}