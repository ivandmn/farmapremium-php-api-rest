<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidEmailException;

readonly class Email
{
    public const MAX_LENGTH = 255;

    public function __construct(private string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException('Invalid email format');
        }

        if (strlen($value) > self::MAX_LENGTH) {
            throw new InvalidEmailException('Email exceeds maximum length');
        }
    }

    public static function fromString(string $email) : static
    {
        return new static($email);
    }

    public function value() : string
    {
        return $this->value;
    }

    public function equals(Email $other) : bool
    {
        return $this->value === $other->value;
    }

    public function __toString() : string
    {
        return $this->value;
    }
}
