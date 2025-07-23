<?php

namespace App\Auth\Test\Unit\Entity\User\ResetPassword;


use App\Auth\Entity\User\User;
use App\Auth\Service\PasswordHasher;
use App\Auth\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers User
 */
class ChangePasswordTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();
        $hasher = $this->createHasher(true, $hash = 'new-hash');

        $user->changePassword(
            'current',
            'new',
            $hasher
        );

        $this->assertEquals($hash, $user->getPasswordHash());
    }

    public function testWrongCurrent(): void
    {
        $user = (new UserBuilder())->active()->build();
        $hasher = $this->createHasher(false, 'new-hash');

        $this->expectExceptionMessage('Incorrect current password.');

        $user->changePassword('wrong-old-password', 'new-password', $hasher);
    }
    public function testByNetwork(): void
    {
        $user = (new UserBuilder())
            ->viaNetwork()
            ->build();

        $hasher = $this->createHasher(false, 'new-hash');
        $this->expectExceptionMessage('User does not have an old password.');

        $user->changePassword(
          'any-old-password',
          'old-password',
          $hasher
        );
    }
    private function createHasher(bool $valid, string $hash): PasswordHasher
    {
        $hasher = $this->createStub(PasswordHasher::class);
        $hasher->method('validate')->willReturn($valid);
        $hasher->method('hash')->willReturn($hash);
        return $hasher;
    }

}