<?php

namespace App\Auth\Test\Unit\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Token;
use App\Auth\Service\PasswordResetTokenSender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Twig\Environment;

class PasswordResetTokenSenderTest extends TestCase
{
    public function testSuccess(): void
    {
        $to = new Email('user@app.ru');
        $token = new Token(Id::generate(), new \DatetimeImmutable());

        $confirmUrl = 'http://test/password/confirm?token=' . $token->getValue();

        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())->method('render')->with(
            $this->equalTo('auth/password/confirm.html.twig'),
            $this->equalTo(['token' => $token])
        )->willReturn($body = '<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createMock(MailerInterface::class);

        $mailer->expects($this->once())->method('send')
            ->willReturnCallback(static function (SymfonyEmail $mail) use ($to, $body) {
                self::assertEquals([new Address($to->getValue())], $mail->getTo());
                self::assertEquals('Reset password', $mail->getSubject());
                self::assertStringContainsString($body, $mail->getHtmlBody());
        });
        $sender = new PasswordResetTokenSender($mailer, $twig);
        $sender->send($to, $token);
    }

    public function testError(): void
    {
        $to = new Email('user@app.ru');
        $token = new Token(Id::generate(), new \DatetimeImmutable());
        $confirmUrl = 'http://test/email/confirm?token=' . $token->getValue();

        $twig = $this->createStub(Environment::class);
        $twig->expects($this->once())->method('render')->willReturn('<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createStub(MailerInterface::class);
        $mailer->expects($this->once())->method('send')->willThrowException(new TransportException());

        $sender = new PasswordResetTokenSender($mailer, $twig);
        $this->expectException(TransportException::class);
        $sender->send($to, $token);
    }
}