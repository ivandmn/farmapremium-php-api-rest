<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\User;

use App\Domain\Exception\InvalidDomainModelArgumentException;

final readonly class UserEmail
{
    private const MAX_LENGTH = 320;

    public function __construct(private string $value)
    {
        $this->ensureIsValid($value);
    }

    public function value() : string
    {
        return $this->value;
    }

    private function ensureIsValid(string $value) : void
    {
        if (empty($value)) {
            throw new InvalidDomainModelArgumentException('User email cannot be empty');
        }

        if (strlen($value) > self::MAX_LENGTH) {
            throw new InvalidDomainModelArgumentException(
                sprintf('User email cannot exceed %d characters', self::MAX_LENGTH)
            );
        }

        if (!filter_var(value: $value, filter: FILTER_VALIDATE_EMAIL)) {
            throw new InvalidDomainModelArgumentException('Invalid user email format');
        }
    }

    public function equals(UserEmail $other) : bool
    {
        return $this === $other;
    }

    public function __toString() : string
    {
        return $this->value;
    }
}