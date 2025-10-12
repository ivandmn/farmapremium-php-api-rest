<?php

declare(strict_types = 1);

namespace App\Application\UseCase\UpdateTask;

use App\Domain\ValueObject\Task\TaskDueDate;
use App\Application\Exception\InvalidDateFormat;
use DateTime;

final class UpdateTaskRequest
{
    private ?DateTime $dueDate;

    public function __construct(
        private string  $taskId,
        private ?string $title,
        private ?string $description,
        private ?string $stauts,
        private ?string $priority,
        ?string         $dueDate
    ) {
        $date = $dueDate !== null ? DateTime::createFromFormat(TaskDueDate::FORMAT, $dueDate) : null;
        if ($date === false) {
            throw new InvalidDateFormat(sprintf('Invalid Due date, must be in format "%s"', TaskDueDate::FORMAT_NAME));
        }

        $this->dueDate = $date;
    }

    public function getTaskId() : string
    {
        return $this->taskId;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function getStatus() : ?string
    {
        return $this->stauts;
    }

    public function getPriority() : ?string
    {
        return $this->priority;
    }

    public function getDueDate() : ?DateTime
    {
        return $this->dueDate;
    }
}
