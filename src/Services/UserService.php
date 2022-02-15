<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private $doctrine;
    public function __construct(
        ManagerRegistry $doctrine,
        private UserPasswordHasherInterface $hasher
    )
    {
        $this->doctrine = $doctrine->getManager();
    }

    public function checkUserId(?int $id = null)
    {
        if(!$id){
            return $id;
        }
        return $this->doctrine->getRepository(User::class)->find($id);
    }


    public function createAndUpdate(User $user, string $password):User
    {
        $user->setPassword($this->hasher->hashPassword($user, $password));

        $this->doctrine->persist($user);
        $this->doctrine->flush();

        return $user;
    }

    public function delete(User $user): bool
    {
        $this->doctrine->remove($user);

        $this->doctrine->flush();

        return true;
    }
}