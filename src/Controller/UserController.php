<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerAMiwRxX\getUserPasswordEncoderService;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * This function allows the user to register on the website
     * The user is redirected to the registration form
     * Once the form has been correctly filled out, the user is redirected to the home page  ????????????????????
     * 
     *  @Route("/inscription", name="user_registration")
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
            return $this->redirectToRoute('user_registrationStepTwo', ['id' => $user->getId()]);
        }

        return $this->render('user/registration.html.twig', [
            'registrationForm' => $registrationForm->createView()
        ]);
    }

    /**
     * This function invite the user to fill out a new form that is mandatory to validate the registration
     * The user has go through this last step of the registration process before he can create a project or offer his skills to others
     * 
     * @Route("/inscription-suite/{id}", name="user_registrationStepTwo")
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

        return $this->render('user/userProfileRegistration.html.twig', [
            'registrationStepTwo' => $registrationStepTwo->createview()
        ]);
    }

    /**
     * This function redirect the user to his personnal information page
     * In this page he can review all the data that is stored about himself and update or delete the data.
     * 
     * @Route("/profil", name="user_profile")
     */
    public function myProfile(): Response
    {
        $user = $this->getUser();
        $userProfileRepo = $this->getDoctrine()->getRepository(UserProfile::class);
        $userProfile = $userProfileRepo->findUserProfileByUserId($user->getId());

        return $this->render('user/myProfile.html.twig', [
            'user' => $user,
            'userProfile' => $userProfile
        ]);
    }

    /**
     * This function is triggered from the "My profile" page when the user clicks on "Modify"
     * The User is redirected to another view that allows him to change the data stored on his profile
     * 
     * @Route("/profil_maj/{id}", name="user_updateProfile")
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
            $em->persist($userProfile);
            $em->flush();

            $this->addFlash('success', 'Les informations ont bien été mise à jour !');

            return $this->redirectToRoute('user_myProfile');
        }

        return $this->render('user/updateProfile.html.twig', [
            'user' => $user,
            'userProfile' => $userProfile,
            'form' => $form->createView()
        ]);
    }

    /**
     * This function allows the user to modify his password
     * 
     * @Route("/mdp_maj/{id}", name="user_updatePassword")
     * 
     * @param [type] $id
     * @param Request $req
     * @return Response
     */
    public function updatePassword($id, Request $req, EncoderFactoryInterface $encoder): Response
    {
        $userloggedIn = $this->getUser();
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($id);

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->getData();

        $form->handleRequest($req);


        if ($userloggedIn && $userloggedIn->getId() === (int) $id && $form->isSubmitted() && $form->isValid()) {
            $previousPassword = $req->request->get('previousPassword');
            $passwordEncoder = $encoder->getEncoder($userloggedIn);

            if (!$passwordEncoder->isPasswordValid($user->getPassword(), $previousPassword, "bcrypt")) {
                $this->addFlash('warning', 'Merci de resaisir les mots de passes. La validation a échoué !');
            } else {
                $newPassword = $form->get('password')->getData();
                $hashed = $passwordEncoder->encodePassword($newPassword, "bcrypt");
                $user->setPassword($hashed);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été mis à jour !');

                return $this->redirectToRoute('user_profile');
            }
        }




        return $this->render('user/updatePassword.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
