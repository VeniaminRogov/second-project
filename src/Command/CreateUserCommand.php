<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create:user';

    protected function configure()
    {
//        $this->addArgument('username', InputArgument::REQUIRED, 'The email of user');
//        $this->addArgument('password', InputArgument::REQUIRED, 'The password of user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('User Creator');

        $helper = $this->getHelper('question');
        $emailQuest = new Question('Enter email of the user: ');
        $passwordQuest = new Question('Enter password of the user: ');
        $email = $helper->ask($input, $output, $emailQuest);
        $password = $helper->ask($input, $output, $passwordQuest);
        dd($email.':'.$password);
//        $io->ask('Enter user email');

        return Command::SUCCESS;
    }
}