<?php

namespace App\Domain\ValueObject\Task;

use App\Domain\Exception\Task\TaskDueDateInPastException;
use DateTime;

final class TaskDueDate
{
    public const FORMAT = \DATE_RFC3339;
    public const FORMAT_NAME = 'RFC3339';

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

    public function value() : DateTime
    {
        return $this->date;
    }

    public function equals(TaskDueDate $other) : bool
    {
        return $this->date->getTimestamp() === $other->date->getTimestamp();
    }

    public function __toString() : string
    {
        return $this->date->format(self::FORMAT);
    }
}
