<?php

namespace App\Auth\Test\Unit\Service;

use App\Auth\Service\PasswordHasher;
use PHPUnit\Framework\TestCase;

class PasswordHasherTest extends TestCase
{
    public function testHash(): void
    {
        $hasher = new PasswordHasher(16);

        $hash = $hasher->hash($password = 'password');
        self::assertNotEmpty($hash);

        self::assertNotEquals($password, $hash);
    }

    public function testEmpty(): void
    {
        $hasher = new PasswordHasher(16);
        $this->expectException(\InvalidArgumentException::class);
        $hash = $hasher->hash('');
    }

    public function testValidate(): void
    {
        $hasher = new PasswordHasher(16);
        $hash = $hasher->hash($password = 'password');

        self::assertTrue($hasher->validate($password, $hash));
        self::assertFalse($hasher->validate('wrong-password', $hash));
    }
}