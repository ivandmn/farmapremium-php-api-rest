<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\Task;

use App\Domain\Exception\Task\InvalidTaskStatusException;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public static function fromString(string $value) : self
    {
        return self::tryFrom($value)
            ?? throw new InvalidTaskStatusException('Invalid task status');
    }

    public function isPending() : bool
    {
        return $this === self::PENDING;
    }

    public function isInProgress() : bool
    {
        return $this === self::IN_PROGRESS;
    }

    public function isCompleted() : bool
    {
        return $this === self::COMPLETED;
    }

    public function equals(TaskStatus $other) : bool
    {
        return $this === $other;
    }

    public function canTransitionTo(TaskStatus $newStatus) : bool
    {
        return match ([$this, $newStatus]) {
            [self::PENDING, self::IN_PROGRESS], [self::IN_PROGRESS, self::COMPLETED] => true,
            default => false
        };
    }
}
