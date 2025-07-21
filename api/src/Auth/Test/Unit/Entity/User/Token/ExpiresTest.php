<?php

namespace App\Auth\Test\Unit\Entity\User\Token;

use App\Auth\Entity\User\Token;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ExpiresTest extends TestCase
{
    public function testNot(): void
    {
        $token = new Token(
            $value = Uuid::uuid4()->toString(),
            $expires = new DateTimeImmutable(),
        );

        $this->assertFalse($token->isExpiredTo($expires->modify('-1 hour')));
        $this->assertTrue($token->isExpiredTo($expires));
        $this->assertTrue($token->isExpiredTo($expires->modify('+1 hour')));

    }
}