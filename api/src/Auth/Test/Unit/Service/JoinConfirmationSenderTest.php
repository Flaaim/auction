<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Service\JoinConfirmationSender;
use App\Frontend\FrontendUrlGenerator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;

/**
 * @covers JoinConfirmationSender
 */
class JoinConfirmationSenderTest extends TestCase
{
    public function testSuccess(): void
    {
        $from = ['test@app.test', 'Test'];

        $to = new Email('user@app.test');
        $token = new Token(Uuid::uuid4()->toString(), new \DateTimeImmutable());

        $confirmUrl = 'http://test/join/confirm?token=' . $token->getValue();

        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())->method('render')->with(
            $this->equalTo('auth/join/confirm.html.twig'),
            $this->equalTo(['token' => $token]),
        )->willReturn($body = '<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');


        $mailer = $this->createMock(MailerInterface::class);

        $mailer->expects($this->once())->method('send')
            ->willReturnCallback(static function (\Symfony\Component\Mime\Email $message) use ($to, $body): void {
                self::assertEquals($to->getValue(), $message->getTo()[0]->getAddress());
                self::assertEquals('Join confirmation', $message->getSubject());
                self::assertEquals($body, $message->getHtmlBody());

            });

        $sender = new JoinConfirmationSender($mailer, $twig);
        $sender->send($to, $token);
    }

    public function testError(): void
    {
        $to = new Email('user@app.test');
        $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable());
        $confirmUrl = 'http://test/join/confirm?token=' . $token->getValue();

        $twig = $this->createStub(Environment::class);
        $twig->method('render')->willReturn('<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createStub(MailerInterface::class);
        $mailer->method('send')->willThrowException(new TransportException());

        $this->expectException(TransportException::class);
        $sender = new JoinConfirmationSender($mailer, $twig);
        $sender->send($to, $token);
    }

}