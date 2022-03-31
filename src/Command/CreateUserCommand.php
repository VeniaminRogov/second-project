<?php

namespace App\Command;

use App\Exceptions\ValidationException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create:user';

    public function __construct(
        private MessageBusInterface $bus,
    )
    {
        parent::__construct();
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

        try {
            $this->bus->dispatch(new \App\Messenger\CreateUserCommand($email, $password));
        } catch (ValidationException $exception) {
            foreach($exception->getConstraintViolationList() as $violation){
                $io->error($violation->getMessage());
            }
            return Command::INVALID;
        }

        return Command::SUCCESS;
    }
}