<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * This function allows the user to register on the website
     * The user is redirected to the registration form
     * Once the form has been correctly filled out, the user is redirected to the home page  ????????????????????
     * 
     *  @Route("/inscription", name="main_registration")
     */
    public function register(Request $req, EntityManagerInterface $emi, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        $registrationForm = $this->createForm(RegistrationType::class, $user);
        $registrationForm->handleRequest($req);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);
            $user->setRoles(['ROLE_USER']);
            $emi->persist($user);
            $emi->flush();

            $this->addFlash('success', 'Félicitations ! Vous êtes maintenant membre de notre troupe de petit ouistiti. Encore quelques informations à nous confier et vous pourrez enfin travailler avec vos semblables.');

            return $this->redirectToRoute('main_home');
        }

        return $this->render('main/registration.html.twig', [
            'registrationForm' => $registrationForm->createView()
        ]);
    }
}
