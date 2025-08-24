<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Frontend\FrontendUrlGenerator;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;

class JoinConfirmationSender
{
    private MailerInterface $mailer;
    private FrontendUrlGenerator $frontend;
    private Environment $twig;

    public function __construct(MailerInterface $mailer,  FrontendUrlGenerator $frontend, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->frontend = $frontend;
        $this->twig = $twig;
    }
    public function send(Email $email, Token $token): void
    {
        $message = (new \Symfony\Component\Mime\Email())
            ->subject('Join confirmation')
            ->to(new Address($email->getValue()))
            ->html($this->twig->render('auth/join/confirm.html.twig', [
                'url' => $this->frontend->generate('join/confirm', ['token' => $token->getValue()])
            ]));

        $this->mailer->send($message);
    }
}