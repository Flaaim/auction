<?php

namespace App\Auth\Test\Unit\Entity\User\JoinByNetwork;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\NetworkIdentity;
use App\Auth\Entity\User\Role;
use App\Auth\Entity\User\User;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class JoinByNetworkTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = User::joinByNetwork(
            $id = Id::generate(),
            $date = new DateTimeImmutable(),
            $email = new Email('some@email.com'),
            $network = new NetworkIdentity('google.com', '000011'),
        );

        self::assertTrue($user->isActive());
        self::assertFalse($user->isWait());

        self::assertEquals($user->getId(), $id);
        self::assertEquals($user->getDate(), $date);
        self::assertEquals($user->getEmail(), $email);

        self::assertEquals(Role::USER, $user->getRole()->getName());

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertEquals($network, $networks[0] ?? null);
    }
}