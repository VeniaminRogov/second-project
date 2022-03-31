<?php

namespace App\Messenger;

use App\Exceptions\ValidationException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ValidatorInterface $validator
    ) {}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $violations = $this->validator->validate($envelope->getMessage());

        if($violations->count() > 0)
        {
            throw new ValidationException($violations);
        }

        return $stack->next()->handle($envelope, $stack);
    }
}