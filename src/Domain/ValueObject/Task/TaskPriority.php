<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\Task;

use App\Domain\Exception\Task\InvalidTaskPriorityException;

enum TaskPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public static function fromString(string $value) : self
    {
        return self::tryFrom($value)
            ?? throw new InvalidTaskPriorityException('Invalid task priority');
    }

    public function isLow() : bool
    {
        return $this === self::LOW;
    }

    public function isMedium() : bool
    {
        return $this === self::MEDIUM;
    }

    public function isHigh() : bool
    {
        return $this === self::HIGH;
    }

    public function isHigherThan(TaskPriority $other) : bool
    {
        return $this->getNumericValue() > $other->getNumericValue();
    }

    public function isLowerThan(TaskPriority $other) : bool
    {
        return $this->getNumericValue() < $other->getNumericValue();
    }

    public function equals(TaskPriority $other) : bool
    {
        return $this === $other;
    }

    public function getNumericValue() : int
    {
        return match ($this) {
            self::LOW => 1,
            self::MEDIUM => 2,
            self::HIGH => 3,
        };
    }
}
