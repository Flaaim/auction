<?php

namespace App\Auth\Test\Unit\Entity\User\JoinByEmail;

use App\Auth\Entity\User\Token;
use App\Auth\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->withJoinConfirmToken($token = $this->createToken())
            ->build();

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        $user->confirmJoin(
            $token->getValue(),
            $token->getExpires()->modify('-1 day')
        );

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertNull($user->getJoinConfirmToken());
    }

    public function testWrong(): void
    {
        $user = (new UserBuilder())
            ->withJoinConfirmToken($token = $this->createToken())
            ->build();

        $this->expectExceptionMessage('Token is invalid.');

        $user->confirmJoin(
            Uuid::uuid4()->toString(),
            $token->getExpires()->modify('-1 day')
        );
    }

    public function testExpired(): void
    {
        $user = (new UserBuilder())
            ->withJoinConfirmToken($token = $this->createToken())
            ->build();

        $this->expectExceptionMessage('Token is expired.');

        $user->confirmJoin(
            $token->getValue(),
            $token->getExpires()->modify('+1 day')
        );
    }

    public function testAlready(): void
    {
        $token = $this->createToken();

        $user = (new UserBuilder())
            ->withJoinConfirmToken($token)
            ->active()
            ->build();

        $this->expectExceptionMessage('Confirmation is not required.');

        $user->confirmJoin(
            $token->getValue(),
            $token->getExpires()->modify('-1 day')
        );
    }
    private function createToken(): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            new \DateTimeImmutable('+1 day')
        );
    }
}