<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create:user';

    public function __construct(
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $hasher,
        private ManagerRegistry $manager,
    )
    {
        parent::__construct();
    }


    protected function configure()
    {
        $this->addArgument('username', InputArgument::OPTIONAL, 'The email of user');
//        $this->addArgument('password', InputArgument::REQUIRED, 'The password of user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User();
        $io = new SymfonyStyle($input, $output);
        $io->title('User Creator');

        $helper = $this->getHelper('question');

        $emailQuest = new Question('Enter email of the user: ');
        $passwordQuest = new Question('Enter password of the user: ');
        $email = $helper->ask($input, $output, $emailQuest);
        $password = $helper->ask($input, $output, $passwordQuest);

        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $user->setIsVerified(true);
        $violations = $this->validator->validate($user);
        if($violations->count() !== 0)
        {
           return Command::INVALID;
        }

        $this->manager->getManager()->persist($user);
        $this->manager->getManager()->flush();

        $io->success('User '.$user->getEmail().' added');

        return Command::SUCCESS;
    }
}