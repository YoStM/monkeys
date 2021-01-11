<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserProfile;
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
        $user = new User();

        $user->setUsername("YoStM");
        $user->setPassword($this->encoder->encodePassword($user, "yostm"));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $userProfile = new UserProfile();

        $userProfile->setFirstName("Yohan");
        $userProfile->setLastName("SAINT-MARTIN");
        $userProfile->setCompanyName("Scribo Ergo Sum");
        $userProfile->setEmail("yo.stm@outlook.com");
        $userProfile->setSiret("12354897654329");
        $userProfile->setActivity("Développeur Web");
        $userProfile->setAboutUser("J'aime le backend et plus spécialement php et Symfony.
                                    Peut-être aussi parce que je ne connais que ça pour le moment. HTML5 - CSS - JS - PHP - Symfony 5.");
        $userProfile->setCredit(500);
        $userProfile->setUserId($user);
        $manager->persist($userProfile);

        $user = new User();

        $user->setUsername("Vico");
        $user->setPassword($this->encoder->encodePassword($user, "vico"));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $userProfile = new UserProfile();

        $userProfile->setFirstName("Victor");
        $userProfile->setLastName("SAINT-MARTIN");
        $userProfile->setCompanyName("Makette de dessin");
        $userProfile->setEmail("vi.stm@outlook.com");
        $userProfile->setSiret("12357957654653");
        $userProfile->setActivity("Graphiste");
        $userProfile->setAboutUser("Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                    Sed at purus in metus mollis ultricies vitae ut orci. 
                                    Duis tempor nulla a massa finibus tincidunt. Ut at suscipit tortor. 
                                    Quisque non dolor quis lacus imperdiet. ");
        $userProfile->setCredit(500);
        $userProfile->setUserId($user);
        $manager->persist($userProfile);

        $manager->flush();
    }
}
