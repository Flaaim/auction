<?php

namespace App\Frontend\Test\Unit;

use App\Frontend\FrontendUrlGenerator;
use PHPUnit\Framework\TestCase;

class FrontendUrlGeneratorTest extends TestCase
{
    public function testEmpty(): void
    {
        $generator = new FrontendUrlGenerator('http://test.ru');
        $url = $generator->generate('');

        $this->assertEquals('http://test.ru', $url);
    }

    public function testPath(): void
    {
        $generator = new FrontendUrlGenerator('http://test.ru');
        $url = $generator->generate('api/users');
        $this->assertEquals('http://test.ru/api/users', $url);
    }

    public function testWithParams(): void
    {
        $generator = new FrontendUrlGenerator('http://test.ru');
        $url = $generator->generate('api/users', [
            'id' => 1,
            'name' => 'test'
        ]);
        $this->assertEquals('http://test.ru/api/users?id=1&name=test', $url);
    }
}