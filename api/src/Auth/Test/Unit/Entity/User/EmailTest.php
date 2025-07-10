<?php

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testSuccess()
    {
        $email = new Email('test@app.ru');
        $this->assertEquals('test@app.ru', $email->getValue());
    }
    public function testCase()
    {
        $email = new Email('TesT@app.ru');
        $this->assertEquals('test@app.ru', $email->getValue());
    }
    public function testIncorrect()
    {
        $this->expectException(\InvalidArgumentException::class);
        $email = new Email('test');
    }
    public function testEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $email = new Email('');
    }
}