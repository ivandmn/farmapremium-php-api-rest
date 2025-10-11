<?php

namespace App\Domain\ValueObject\Task;

use App\Domain\Exception\Task\TaskDueDateInPastException;
use DateTime;
use App\Domain\ValueObject\Date;

final class TaskDueDate extends Date
{
    public const FORMAT = \DATE_ATOM;

    public function __construct(DateTime $date)
    {
        parent::__construct($date);

        if ($this->value < new DateTime('now')) {
            throw new TaskDueDateInPastException('Due date cannot be in the past');
        }
    }
}
