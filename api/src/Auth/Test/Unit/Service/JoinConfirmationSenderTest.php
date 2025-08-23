<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Service\JoinConfirmationSender;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

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
        $confirmUrl = '/join/confirm?token=' . $token->getValue();

        $mailer = $this->createMock(MailerInterface::class);

        $mailer->expects($this->once())->method('send')
            ->willReturnCallback(static function (\Symfony\Component\Mime\Email $message) use ($from, $to, $confirmUrl): int {
                self::assertEquals([new Address(...$from)], $message->getFrom());
                self::assertEquals([new Address($to->getValue())], $message->getTo());
                self::assertEquals('Join confirmation', $message->getSubject());
                self::assertStringContainsString($confirmUrl, $message->getHtmlBody());
                return 1;
            });

        $sender = new JoinConfirmationSender($mailer, $from);
        $sender->send($to, $token);
    }

    public function testError(): void
    {
        $from = ['test@app.test', 'Test'];
        $to = new Email('user@app.test');
        $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable());

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())
            ->method('send')
            ->willThrowException(new \RuntimeException('SMTP error'));

        $sender = new JoinConfirmationSender($mailer, $from);

        $this->expectException(RuntimeException::class);
        $sender->send($to, $token);
    }
}