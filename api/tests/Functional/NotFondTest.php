<?php

namespace Test\Functional;

class NotFondTest extends WebTestCase
{
    public function testNotFond(): void
    {
        $response = $this->app()->handle(self::json('GET', '/test'));

        self::assertEquals(404, $response->getStatusCode());

    }
}