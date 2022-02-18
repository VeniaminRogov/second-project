<?php

namespace App\Mail;

use App\Repository\UserRepository;
use App\Services\MailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MailHandler
{
    public function __construct(
        private MailService $mailer,
        private UserRepository $userRepository,
    )
    {
    }

    public function __invoke(MailNotification $mailNotification)
    {
        if (MailNotification::USER_REGISTRATION == $mailNotification->getType()){
            $this->onRegistrationUser($mailNotification->getId(), $mailNotification->getVerificationUrl());
        }
    }

    public function onRegistrationUser($id, $verificationUrl){
        $user = $this->userRepository->find($id);
        $email = (new TemplatedEmail())
            ->from('veniamin.r@zimalab.com')
            ->to($user->getEmail())
            ->subject('Verify your email!')
            ->htmlTemplate('email/verify_email.html.twig')
            ->context([
                'username' => $user->getUserIdentifier(),
                'signed_url' => $verificationUrl
            ]);

        $this->mailer->sendEmailAccrosMessanger($email);
    }
}