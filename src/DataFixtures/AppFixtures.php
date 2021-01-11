<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use App\Entity\UserProfile;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();

        $user1->setUsername("YoStM");
        $user1->setPassword($this->encoder->encodePassword($user, "yostm"));
        $user1->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $userProfile1 = new UserProfile();

        $userProfile1->setFirstName("Yohan");
        $userProfile1->setLastName("SAINT-MARTIN");
        $userProfile1->setCompanyName("Scribo Ergo Sum");
        $userProfile1->setEmail("yo.stm@outlook.com");
        $userProfile1->setSiret("12354897654329");
        $userProfile1->setActivity("Développeur Web");
        $userProfile1->setAboutUser("J'aime le backend et plus spécialement php et Symfony.
                                    Peut-être aussi parce que je ne connais que ça pour le moment. HTML5 - CSS - JS - PHP - Symfony 5.");
        $userProfile1->setCredit(500);
        $userProfile1->setUserId($user1);
        $manager->persist($userProfile1);

        $user2 = new User();

        $user2->setUsername("Vico");
        $user2->setPassword($this->encoder->encodePassword($user, "vico"));
        $user2->setRoles(['ROLE_USER']);
        $manager->persist($user2);

        $userProfile2 = new UserProfile();

        $userProfile2->setFirstName("Victor");
        $userProfile2->setLastName("SAINT-MARTIN");
        $userProfile2->setCompanyName("Makette de dessin");
        $userProfile2->setEmail("vi.stm@outlook.com");
        $userProfile2->setSiret("12357957654653");
        $userProfile2->setActivity("Graphiste");
        $userProfile2->setAboutUser("Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                    Sed at purus in metus mollis ultricies vitae ut orci. 
                                    Duis tempor nulla a massa finibus tincidunt. Ut at suscipit tortor. 
                                    Quisque non dolor quis lacus imperdiet. ");
        $userProfile2->setCredit(500);
        $userProfile2->setUserId($user2);
        $manager->persist($userProfile2);

        $manager->flush();

        $date = date_create("11/01/2021");
        $interval = 7;
        $projectNbr = 1;

        for ($i = 0; $i < 20; $i++) {
            $project = new Project();
            $project->setTitle("Project numéro " . $projectNbr);
            $project->setDescription("Voici les détails du projet numéro " . $projectNbr);
            $project->setActive(true);
            $project->setCreateDate($date);
            $project->setUserId($user1->getId());


            $date -= $interval;
        }
    }
}
