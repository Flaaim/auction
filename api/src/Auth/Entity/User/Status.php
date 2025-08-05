<?php

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class Status
{
    public const ACTIVE = 'active';
    public const WAIT = 'wait';
    private string $name;
    public function __construct(string $name)
    {

        Assert::oneOf($name, [
            self::WAIT,
            self::ACTIVE,
        ]);
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

    public function getName(): string
    {
        return $this->name;
    }
}