<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class JoinConfirmationSender
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function send(Email $email, Token $token): void
    {
        $message = (new \Symfony\Component\Mime\Email())
            ->subject('Join confirmation')
            ->to(new Address($email->getValue()))
            ->html('/join/confirm?' . http_build_query([
                    'token' => $token->getValue(),
            ]));

        $this->mailer->send($message);
    }
}