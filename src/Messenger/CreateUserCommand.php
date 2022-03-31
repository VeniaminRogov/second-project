<?php

namespace App\Messenger;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserCommand
{
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private string $email;
    private string $plainPassword;

    public static function create($email, $password)
    {
        $command = new self;
        
        $this->email = $email;
        $this->plainPassword = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}