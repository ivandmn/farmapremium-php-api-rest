<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\Task;

use App\Domain\ValueObject\Uuid;
use App\Domain\Exception\InvalidDomainModelArgumentException;
use App\Domain\Exception\UuidInvalidException;

final readonly class TaskId extends Uuid
{
    public static function generate() : self
    {
        return new self(parent::generate()->value());
    }

    public static function fromString(string $value) : self
    {
        try {
            return new self($value);
        } catch (UuidInvalidException $exception) {
            throw new InvalidDomainModelArgumentException("Invalid Task ID", InvalidDomainModelArgumentException::CODE_INVALID_DOMAIN_MODEL, $exception);
        }
    }
}