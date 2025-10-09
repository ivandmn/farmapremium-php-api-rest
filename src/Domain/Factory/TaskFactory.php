<?php

namespace App\Domain\Factory;

use App\Domain\Model\Task;
use App\Domain\ValueObject\Task\TaskDescription;
use App\Domain\ValueObject\Task\TaskDueDate;
use App\Domain\ValueObject\Task\TaskId;
use App\Domain\ValueObject\Task\TaskPriority;
use App\Domain\ValueObject\Task\TaskStatus;
use App\Domain\ValueObject\Task\TaskTitle;
use DateTimeImmutable;

final class TaskFactory
{
    public function register(
        TaskTitle         $title,
        TaskDescription   $description,
        TaskPriority      $priority = TaskPriority::LOW,
        TaskDueDate $dueDate = null,
    ) : Task {
        return new Task(
            TaskId::new(),
            $title,
            $description,
            TaskStatus::PENDING,
            $priority,
            null,
            $dueDate,
            new DateTimeImmutable('now'),
            null
        );
    }
}
