<?php

namespace App\Auth\Entity\User;

class Status
{
    private const ACTIVE = 'active';
    private const WAIT = 'wait';

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function wait(): self
    {
        return new self(self::WAIT);
    }
    public static function active(): self
    {
        return new self(self::ACTIVE);
    }
    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }
    public function isWait(): bool
    {
        return $this->name === self::WAIT;
    }
}