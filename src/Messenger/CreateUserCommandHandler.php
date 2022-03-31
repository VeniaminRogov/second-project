<?php

namespace App\Messenger;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class CreateUserCommandHandler
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private ManagerRegistry $em
    )
    {
    }

    public function __invoke(CreateUserCommand $command)
    {
        $user = new User();

        $user->setEmail($command->getEmail());
        $user->setPassword($this->hasher->hashPassword($user, $command->getPlainPassword()));
        $user->setIsVerified(true);

        $this->em->getManager()->persist($user);
        $this->em->getManager()->flush();
    }
}