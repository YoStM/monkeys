<?php

namespace App\Command;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// This class is auto-generated when using php bin/console make:command
// This allows us to create terminal command
class CreateAdminCommand extends Command
{
    // Here I choose to create a command that generates an Admin and register the Admin Access into the Database.
    protected static $defaultName = 'app:create-admin';

    private $emi;
    private $encoder;

    public function __construct(EntityManagerInterface $emi, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct();
        $this->emi = $emi;
        $this->encoder = $encoder;
    }

    // This function allows us to configure the command.
    protected function configure()
    {
        $this
            ->setDescription('Creates admin user')
            ->addArgument('arg1', InputArgument::REQUIRED, 'username')
            ->addArgument('arg2', InputArgument::REQUIRED, 'Password')
            ->setHelp('This command allows you to create the Admin access and needs two arguments. First argument is the Username and the second argument is the password');
    }

    // This Execute the command
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // This allows us to get data that is inserted in terminal and store this data into our code.
        $io = new SymfonyStyle($input, $output);
        // Here we store first argument into $arg1 variable
        $arg1 = $input->getArgument('arg1');
        // Here we store second argument into $arg2 variable
        $arg2 = $input->getArgument('arg2');

        // If and only if we get both arguments from the terminal command then we execute the command.
        if ($arg1 && $arg2) {

            // We have both argument so we instanciate the Admin
            $admin = new Admin();

            // We use the argument we got earlier in the code to set the admin username, password and role
            $admin->setUsername($arg1);
            // Here we hash the password using Classes and functions provided by symfony
            $hashed = $this->encoder->encodePassword($admin, $arg2);
            $admin->setPassword($hashed);
            $admin->setRoles(['ROLE_ADMIN']);

            // Here we prepare the registration of the data into our database
            $this->emi->persist($admin);
            // Here we finally store the data into our database.
            $this->emi->flush();

            // As a reminder, we display the value the user just inserted
            $io->note(sprintf('You passed an argument: %s, as username', $arg1));
            $io->note(sprintf('You passed an argument: %s, as paswword', $arg2));
        }

        // If all goes well, we confirm the user that the data has been properly stored into our database.
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
