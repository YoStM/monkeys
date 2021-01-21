<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Project;
use App\Entity\Category;
use App\Entity\ProjectOwner;
use App\Entity\UserProfile;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
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

        $date = new DateTime("now", new DateTimeZone("Europe/Paris"));
        $interval = 1;
        $interval2 = 2;
        $projectNbr = 1;

        $category = new Category();
        $category->setLabel("Développement Web");
        $manager->persist($category);

        $manager->flush();

        $category2 = new Category();
        $category2->setLabel("Graphisme");
        $manager->persist($category2);

        $manager->flush();

        $category3 = new Category();
        $category3->setLabel("Administration serveur");
        $manager->persist($category3);

        $manager->flush();


        $user1 = new User();

        $user1->setUsername("YoStM");
        $user1->setPassword($this->encoder->encodePassword($user1, "yostm"));
        $user1->setRoles(['ROLE_USER']);

        $manager->flush();


        $userProfile1 = new UserProfile();

        $userProfile1->setFirstName("Yohan");
        $userProfile1->setLastName("SAINT-MARTIN");
        $userProfile1->setCompanyName("Scribo Ergo Sum");
        $userProfile1->setEmail("yo.stm@outlook.com");
        $userProfile1->setSiret("12354897654329");
        $userProfile1->setActivity("Développeur Web");
        $userProfile1->setAboutUser("J'aime le backend et plus spécialement php et Symfony. Peut-être aussi parce que je ne connais que ça pour le moment. HTML5 - CSS - JS - PHP - Symfony 5.");
        $userProfile1->setCredit(500);
        $userProfile1->setUserId($user1);


        $manager->persist($userProfile1);
        $manager->persist($user1);
        $manager->flush();

        for ($i = 0; $i < 5; $i++) {

            $interval3 = $interval * $i;

            $projectOwner = new ProjectOwner();
            $projectOwner->setUserId($user1);
            $manager->persist($projectOwner);
            $manager->flush();

            $project = new Project();
            $project->setCategoryId($category2);
            $project->setTitle("Project numéro " . $projectNbr);
            $project->setDescription("Voici les détails du projet numéro " . $projectNbr . " : Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sunt quaerat, laboriosam corporis aperiam obcaecati accusamus.");
            $project->setActive(true);
            $project->setCreateDate($date->modify("-$interval3 day"));
            $project->setOwnerId($projectOwner);
            $manager->persist($project);
            $manager->flush();
            $projectNbr++;
        }

        for ($i = 0; $i < 5; $i++) {

            $interval3 = $interval * $i;

            $projectOwner = new ProjectOwner();
            $projectOwner->setUserId($user1);
            $manager->persist($projectOwner);
            $manager->flush();

            $project = new Project();
            $project->setCategoryId($category2);
            $project->setTitle("Project numéro " . $projectNbr);
            $project->setDescription("Voici les détails du projet numéro " . $projectNbr . " : Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sunt quaerat, laboriosam corporis aperiam obcaecati accusamus.");
            $project->setActive(true);
            $project->setCreateDate($date->modify("-$interval3 day"));
            $project->setOwnerId($projectOwner);
            $manager->persist($project);
            $manager->flush();
            $projectNbr++;
        }



        $user2 = new User();

        $user2->setUsername("Vico");
        $user2->setPassword($this->encoder->encodePassword($user2, "vico"));
        $user2->setRoles(['ROLE_USER']);
        $manager->persist($user2);
        $manager->flush();

        $userProfile2 = new UserProfile();

        $userProfile2->setFirstName("Victor");
        $userProfile2->setLastName("SAINT-MARTIN");
        $userProfile2->setCompanyName("Makette de dessin");
        $userProfile2->setEmail("vi.stm@outlook.com");
        $userProfile2->setSiret("12357957654653");
        $userProfile2->setActivity("Graphiste");
        $userProfile2->setAboutUser("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at purus in metus mollis ultricies vitae ut orci. Duis tempor nulla a massa finibus tincidunt. Ut at suscipit tortor. Quisque non dolor quis lacus imperdiet. ");
        $userProfile2->setCredit(500);
        $userProfile2->setUserId($user2);
        $manager->persist($userProfile2);
        $manager->flush();

        for ($i = 0; $i < 5; $i++) {

            $interval3 = $interval2 * $i;

            $projectOwner = new ProjectOwner();
            $projectOwner->setUserId($user2);
            $manager->persist($projectOwner);
            $manager->flush();

            $project = new Project();
            $project->setCategoryId($category2);
            $project->setTitle("Project numéro " . $projectNbr);
            $project->setDescription("Voici les détails du projet numéro " . $projectNbr . " : Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sunt quaerat, laboriosam corporis aperiam obcaecati accusamus.");
            $project->setActive(true);
            $project->setCreateDate($date->modify("-$interval3 day"));
            $project->setOwnerId($projectOwner);
            $manager->persist($project);
            $manager->flush();
            $projectNbr++;
        }

        for ($i = 0; $i < 5; $i++) {

            $interval3 = $interval2 * $i;

            $projectOwner = new ProjectOwner();
            $projectOwner->setUserId($user2);
            $manager->persist($projectOwner);
            $manager->flush();

            $project = new Project();
            $project->setCategoryId($category2);
            $project->setTitle("Project numéro " . $projectNbr);
            $project->setDescription("Voici les détails du projet numéro " . $projectNbr . " : Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sunt quaerat, laboriosam corporis aperiam obcaecati accusamus.");
            $project->setActive(true);
            $project->setCreateDate($date->modify("-$interval3 day"));
            $project->setOwnerId($projectOwner);
            $manager->persist($project);
            $manager->flush();
            $projectNbr++;
        }

        $user3 = new User();

        $user3->setUsername("MaX");
        $user3->setPassword($this->encoder->encodePassword($user3, "max"));
        $user3->setRoles(['ROLE_USER']);
        $manager->persist($user3);
        $manager->flush();

        $userProfile3 = new UserProfile();

        $userProfile3->setFirstName("Maxime");
        $userProfile3->setLastName("Bielmann");
        $userProfile3->setCompanyName("Lewys");
        $userProfile3->setEmail("ma.bielmann@outlook.com");
        $userProfile3->setSiret("12357456254653");
        $userProfile3->setActivity("Ux designer");
        $userProfile3->setAboutUser("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at purus in metus mollis ultricies vitae ut orci. Duis tempor nulla a massa finibus tincidunt. Ut at suscipit tortor. Quisque non dolor quis lacus imperdiet. ");
        $userProfile3->setCredit(500);
        $userProfile3->setUserId($user3);
        $manager->persist($userProfile3);
        $manager->flush();

        for ($i = 0; $i < 5; $i++) {

            $interval3 = $interval2 * $i;

            $projectOwner = new ProjectOwner();
            $projectOwner->setUserId($user3);
            $manager->persist($projectOwner);
            $manager->flush();

            $project = new Project();
            $project->setCategoryId($category2);
            $project->setTitle("Project numéro " . $projectNbr);
            $project->setDescription("Voici les détails du projet numéro " . $projectNbr . " : Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sunt quaerat, laboriosam corporis aperiam obcaecati accusamus.");
            $project->setActive(true);
            $project->setCreateDate($date->modify("-$interval3 day"));
            $project->setOwnerId($projectOwner);
            $manager->persist($project);
            $manager->flush();
            $projectNbr++;
        }

        for ($i = 0; $i < 5; $i++) {
            $interval3 = $interval2 * $i;

            $projectOwner = new ProjectOwner();
            $projectOwner->setUserId($user3);
            $manager->persist($projectOwner);
            $manager->flush();

            $project = new Project();
            $project->setCategoryId($category2);
            $project->setTitle("Project numéro " . $projectNbr);
            $project->setDescription("Voici les détails du projet numéro " . $projectNbr . " : Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sunt quaerat, laboriosam corporis aperiam obcaecati accusamus.");
            $project->setActive(true);
            $project->setCreateDate($date->modify("-$interval3 day"));
            $project->setOwnerId($projectOwner);
            $manager->persist($project);
            $manager->flush();
            $projectNbr++;
        }
    }
}
