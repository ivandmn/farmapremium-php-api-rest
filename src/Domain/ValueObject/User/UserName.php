<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\User;

use App\Domain\Exception\User\InvalidUserNameException;

final readonly class UserName
{
    public const MAX_LENGTH = 255;

    public function __construct(private string $value)
    {
        if (empty($value)) {
            throw new InvalidUserNameException('User name cannot be empty');
        }

        if (strlen($value) > self::MAX_LENGTH) {
            throw new InvalidUserNameException('User name exceeds maximum characters length');
        }
    }

    public static function fromString(string $value) : self
    {
        return new self($value);
    }

    public function value() : string
    {
        return $this->value;
    }

    public function equals(UserName $other) : bool
    {
        return $this === $other;
    }

    public function __toString() : string
    {
        return $this->value;
    }
}
