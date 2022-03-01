<?php

namespace App\Mail;
class MailNotification
{
    public const USER_REGISTRATION = 'client.registration';
    public const CREATE_ORDER = 'order.create';

    public function __construct(
        private int     $id,
        private string  $type,
        private ?string $verificationUrl = null,
        private ?string $resetToken = null,
        public ?int $orderId = null
    )
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getVerificationUrl()
    {
        return $this->verificationUrl;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }
}