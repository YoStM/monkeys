<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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

            // Redirect the user to the second step of the registration process
            // We have to find a way to associate the user which has been instanciated in this function, to the next function "registrationStepTwo"
            // We simply add an argument to redirectToRoute() that specifies the id of the user we just registered in our database
            // the id will then be used in the next function to refer to the user we just registered into the next function.
            return $this->redirectToRoute('main_registrationStepTwo', ['id' => $user->getId()]);
        }

        return $this->render('main/registration.html.twig', [
            'registrationForm' => $registrationForm->createView()
        ]);
    }

    /**
     * This function invite the user to fill out a new form that is mandatory to validate the registration
     * The user has go through this last step of the registration process before he can create a project or offer his skills to others
     * 
     * @Route("/inscription-suite/{id}", name="main_registrationStepTwo")
     */
    public function registerStepTwo($id, Request $req, EntityManagerInterface $emi): Response
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($id);
        $userProfile = new UserProfile();

        $registrationStepTwo = $this->createForm(UserProfileType::class, $userProfile);
        $registrationStepTwo->handleRequest($req);

        if ($registrationStepTwo->isSubmitted() && $registrationStepTwo->isValid()) {
            $userProfile->setCredit(500);
            $userProfile->setUserId($user);
            $emi->persist($userProfile);
            $emi->flush();

            $this->addFlash('success', 'Vos données ont bien été enregistrées. Vous pouvez les consulter et les modifier à tout moment depuis la page "Mon profil" !');

            return $this->redirectToRoute('main_home');
        }

        return $this->render('main/userProfileRegistration.html.twig', [
            'registrationStepTwo' => $registrationStepTwo->createview()
        ]);
    }

    /**
     * This function redirect the user to his personnal information page
     * In this page he can review all the data that is stored about himself and update or delete the data.
     * 
     * @Route("/profil", name="main_myProfile")
     */
    public function myProfile(): Response
    {
        $user = $this->getUser();
        $userProfileRepo = $this->getDoctrine()->getRepository(UserProfile::class);
        $userProfile = $userProfileRepo->findUserProfileByUserId($user->getId());

        return $this->render('main/myProfile.html.twig', [
            'user' => $user,
            'userProfile' => $userProfile
        ]);
    }

    /**
     * This function is triggered from the "My profile" page when the user clicks on "Modify"
     * The User is redirected to another view that allows him to change the data stored on his profile
     * 
     * @Route("/profil-maj/{id}", name="main_myProfileUpdate")
     */
    public function updateProfil($id, Request $req): Response
    {
        $user = $this->getUser();
        $userProfileRepo = $this->getDoctrine()->getRepository(UserProfile::class);
        $userProfile = $userProfileRepo->findUserProfileByUserId($user->getId());

        if (!$userProfile) {
            throw $this->createNotFoundException('There are no user profil with the following id: ' . $id);
        }

        $form = $this->createFormBuilder($userProfile)
            ->add('email', EmailType::class)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('companyName', TextType::class)
            ->add('siret', TextType::class)
            ->add('activity', TextType::class)
            ->add('aboutUser', TextareaType::class, [
                'attr' => [
                    'rows' => 7,
                    'cols' => 35
                ],
                'label' => 'Ma personalité :'
            ])
            ->getForm();

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $userProfile = $form->getData();
            $em->flush();

            $this->addFlash('success', 'Les informations ont bien été mise à jour !');

            return $this->redirectToRoute('main_myProfile');
        }

        return $this->render('main/myProfileUpdate.html.twig', [
            'user' => $user,
            'userProfile' => $userProfile,
            'form' => $form->createView()
        ]);
    }

    /**
     * This function allows the user to modify his password
     * 
     * @Route("/mdp_maj/{id}", name="main_passwordUpdate")
     * 
     * @param [type] $id
     * @param Request $req
     * @return Response
     */
    public function updateCredentials($id, Request $req, UserPasswordEncoderInterface $encoder): Response
    {

        $user = $this->getUser();
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $userRepo->find($id);

        $this->denyAccessUnlessGranted("ROLE_USER");

        $form = $this->createFormBuilder($user)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques',
                'required' => true,
                'first_options' => array('label' => 'Mot de passe :'),
                'second_options' => array('label' => 'Répéter mot de passe :'),
            ])
            ->getForm();
        $form->handleRequest($req);

        $currentPassword = $this->createFormBuilder($user)
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe actuel :'
            ])
            ->getForm();
        $currentPassword->handleRequest($req);

        dump($user->getPassword());

        if ($user->getId() === $userRepo && $form->isSubmitted()) {

            $hashed = $encoder->encodePassword($user, $user->getPassword());
            dump($hashed);
            $user->setPassword($hashed);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }
        return $this->render('main/passwordUpdate.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'currentPassword' => $currentPassword->createView()
        ]);
    }
}
