<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidUuidException;
use Ramsey\Uuid\Uuid as RamseyUuid;

readonly class Uuid
{
    public function __construct(private string $value)
    {
        if (!RamseyUuid::isValid($value)) {
            throw new InvalidUuidException('Invalid Uuid format');
        }
    }

    public static function from(string $uuid) : static
    {
        return new static($uuid);
    }

    public static function new() : static
    {
        return new static(RamseyUuid::uuid7()->toString());
    }

    public function value() : string
    {
        return $this->value;
    }

    public function equals(Uuid $other) : bool
    {
        return $this->value === $other->value;
    }

    public function __toString() : string
    {
        return $this->value;
    }
}
