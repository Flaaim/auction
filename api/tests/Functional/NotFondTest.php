<?php

namespace Test\Functional;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
class NotFondTest extends WebTestCase
{
    use ArraySubsetAsserts;
    public function testNotFond(): void
    {
        $response = $this->app()->handle(self::json('GET', '/test'));

        self::assertEquals(404, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());


        $data = Json::decode($body);

        self::assertArraySubset([
            'message' => '404 Not Found',
        ], $data);
    }
}