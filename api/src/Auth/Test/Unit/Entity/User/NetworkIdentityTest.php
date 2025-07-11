<?php

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\NetworkIdentity;
use PHPUnit\Framework\TestCase;

class NetworkIdentityTest extends TestCase
{
    public function testSuccess(): void
    {
        $networkIdentity = new NetworkIdentity($network = 'google.com', $identity = 'google-1');

        self::assertEquals($network, $networkIdentity->getNetwork());
        self::assertEquals($identity, $networkIdentity->getIdentity());
    }

    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new NetworkIdentity('google.com', '');

        $this->expectException(\InvalidArgumentException::class);
        new NetworkIdentity('', 'google-1');
    }

    public function testEqual(): void
    {
        $network = new NetworkIdentity($name = 'google.com', $identity = 'google-1');

        self::assertTrue($network->isEqualTo(new NetworkIdentity($name, 'google-1')));
        self::assertFalse($network->isEqualTo(new NetworkIdentity($name, 'google-2')));
        self::assertFalse($network->isEqualTo(new NetworkIdentity('vk', 'vk-1')));
    }
}