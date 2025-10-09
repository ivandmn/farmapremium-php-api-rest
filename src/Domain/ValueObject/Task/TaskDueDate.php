<?php

namespace App\Domain\ValueObject\Task;

use App\Domain\Exception\Task\TaskDueDateInPastException;
use DateTimeImmutable;

final class TaskDueDate
{
    public function __construct(private DateTimeImmutable $date)
    {
        if ($date < new DateTimeImmutable('now')) {
            throw new TaskDueDateInPastException('Due date cannot be in the past');
        }
    }

    public static function fromDate(DateTimeImmutable $date) : self
    {
        return new self($date);
    }

    public function equals(self $other) : bool
    {
        return $this->date->getTimestamp() === $other->date->getTimestamp();
    }

    public function value() : DateTimeImmutable
    {
        return $this->date;
    }
}
