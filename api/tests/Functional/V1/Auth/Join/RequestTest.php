<?php

namespace Test\Functional\V1\Auth\Join;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            RequestFixture::class,
        ]);
    }
    public function testMethod(): void
    {
        $response = $this->app()->handle(self::json('GET', 'v1/auth/join'));

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', 'v1/auth/join', [
            'email' => 'new-user1@app.test',
            'password' => 'new-password',
        ]));

        $this->assertEquals(201, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testExisting(): void
    {
        $response = $this->app()->handle(self::json('POST', 'v1/auth/join', [
            'email' => 'existing@app.test',
            'password' => 'new-password',
        ]));

        $this->assertEquals(409, $response->getStatusCode());
        $this->assertJson($body = (string)$response->getBody());

        $this->assertEquals([
            'message' => 'User already exists.'
        ], Json::decode($body));
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', 'v1/auth/join', []));

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testNotValid(): void
    {
        $response = $this->app()->handle(self::json('POST', 'v1/auth/join', [
            'email' => 'invalid',
            'password' => '',
        ]));

        $this->assertEquals(500, $response->getStatusCode());
    }
}