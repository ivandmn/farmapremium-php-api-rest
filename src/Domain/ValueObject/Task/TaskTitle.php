<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\Task;

use App\Domain\Exception\Task\InvalidTaskTitleException;

final readonly class TaskTitle
{
    public const MAX_LENGTH = 255;

    public function __construct(private string $value)
    {
        if (empty($value)) {
            throw new InvalidTaskTitleException(
                'Task title cannot be empty'
            );
        }

        if (strlen($value) > self::MAX_LENGTH) {
            throw new InvalidTaskTitleException('Task description exceeds maximum characters length');
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

    public function equals(TaskTitle $other) : bool
    {
        return $this === $other;
    }

    public function __toString() : string
    {
        return $this->value;
    }
}
