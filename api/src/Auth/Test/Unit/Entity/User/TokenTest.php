<?php

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Token;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class TokenTest extends TestCase
{
    public function testSuccess(): void
    {
        $token = new Token($value = Uuid::uuid4()->toString(), $datetime = new DateTimeImmutable());
        $this->assertSame($value, $token->getValue());
        $this->assertSame($datetime, $token->getExpires());
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();
        $token = new Token(mb_strtoupper($value), new DateTimeImmutable());
        $this->assertSame($value, $token->getValue());
    }
    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Token('', $datetime = new DateTimeImmutable());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Token('123213', new DateTimeImmutable());
    }
}