<?php

namespace App\Auth\Test\Unit\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Token;
use App\Auth\Service\NewEmailConfirmTokenSender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;
use Symfony\Component\Mime\Email as SymfonyEmail;
class NewEmailConfirmTokenSenderTest extends TestCase
{
    public function testSuccess(): void
    {
        $to = new Email('user@app.ru');
        $token = new Token(Id::generate(), new \DateTimeImmutable());
        $confirmUrl = 'http://test/password/confirm?token=' . $token->getValue();

        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())->method('render')->with(
            $this->equalTo('auth/email/confirm.html.twig'),
            $this->equalTo(['token' => $token])
        )->willReturn($body = '<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->method('send')->willReturnCallback(function (SymfonyEmail $mail) use ($body, $to) {
            self::assertEquals([new Address($to->getValue())], $mail->getTo());
            self::assertEquals('New Email Confirmation', $mail->getSubject());
            self::assertEquals($body, $mail->getHtmlBody());
        });

        $sender = new NewEmailConfirmTokenSender($mailer, $twig);
        $sender->send($to, $token);
    }

    public function testFail(): void
    {
        $to = new Email('user@app.ru');
        $token = new Token(Id::generate(), new \DateTimeImmutable());
        $confirmUrl = 'http://test/password/confirm?token=' . $token->getValue();

        $twig = $this->createStub(Environment::class);
        $twig->expects($this->once())->method('render')->willReturn('<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createStub(MailerInterface::class);
        $mailer->method('send')->willThrowException(new TransportException());

        $sender = new NewEmailConfirmTokenSender($mailer, $twig);
        $this->expectException(TransportException::class);
        $sender->send($to, $token);

    }
}