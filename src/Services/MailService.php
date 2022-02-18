<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(Client $client)
    {
        $email = (new TemplatedEmail())
            ->from('veniamin.r@zimalab.com')
            ->to('rogov.veniamin@gmail.com')
            ->subject('test')
            ->htmlTemplate('email/add_client_message.html.twig')
            ->context([
                'username' => $client->getUser()->getUserIdentifier(),
                'client' => $client,
                'expiration_date' => new \DateTime()
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            dump($e->getDebug());
        }
    }

    public function sendEmailAccrosMessanger($email){
        $this->mailer->send($email);
    }

    public function sendEmailToVerify(User $user, string $to, $signedUrl){
        $email = (new TemplatedEmail())
            ->from('veniamin.r@zimalab.com')
            ->to($to)
            ->subject('Verify your email!')
            ->htmlTemplate('email/verify_email.html.twig')
            ->context([
                'username' => $user->getUserIdentifier(),
                'signed_url' => $signedUrl
            ]);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            dump($e->getDebug());
        }
    }

    public function sendEmailToResetPassword(string $to, $resetToken){
        $email = (new TemplatedEmail())
            ->from(new Address('veniamin.r@zimalab.com', 'ResetPasswordBot'))
            ->to($to)
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            dump($e->getDebug());
        }
    }
}