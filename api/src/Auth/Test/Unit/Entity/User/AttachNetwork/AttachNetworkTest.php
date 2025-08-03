<?php

namespace App\Auth\Test\Unit\Entity\User\AttachNetwork;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use App\Auth\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class AttachNetworkTest extends TestCase
{

    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $user->attachNetwork($network = new Network('google', '0001'));

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertEquals($network, $networks[0] ?? null);
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $network = new Network('google', '0001');
        $user->attachNetwork($network);
        $this->expectExceptionMessage('Network is already attached.');
        $user->attachNetwork($network);

    }

}