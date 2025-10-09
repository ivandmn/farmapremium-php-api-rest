<?php

namespace App\Domain\ValueObject\Task;

use App\Domain\Exception\Task\TaskDueDateInPastException;
use DateTime;
use DateTimeImmutable;

final class TaskDueDate
{
    public function __construct(private DateTime $date)
    {
        if ($date < new DateTime('now')) {
            throw new TaskDueDateInPastException('Due date cannot be in the past');
        }
    }

    public static function fromDate(DateTime $date) : self
    {
        return new self($date);
    }

    public function equals(self $other) : bool
    {
        return $this->date->getTimestamp() === $other->date->getTimestamp();
    }

    public function value() : DateTime
    {
        return $this->date;
    }
}
