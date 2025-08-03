<?php

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Network;
use PHPUnit\Framework\TestCase;

class NetworkTest extends TestCase
{
    public function testSuccess(): void
    {
        $networkIdentity = new Network($network = 'google.com', $identity = 'google-1');

        self::assertEquals($network, $networkIdentity->getNetwork());
        self::assertEquals($identity, $networkIdentity->getIdentity());
    }

    public function testEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Network('google.com', '');

        $this->expectException(\InvalidArgumentException::class);
        new Network('', 'google-1');
    }

    public function testEqual(): void
    {
        $network = new Network($name = 'google.com', $identity = 'google-1');

        self::assertTrue($network->isEqualTo(new Network($name, 'google-1')));
        self::assertFalse($network->isEqualTo(new Network($name, 'google-2')));
        self::assertFalse($network->isEqualTo(new Network('vk', 'vk-1')));
    }
}