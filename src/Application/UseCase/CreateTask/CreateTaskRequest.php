<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateTask;

use App\Application\Exception\InvalidDateFormat;
use App\Domain\ValueObject\Task\TaskDueDate;

final class CreateTaskRequest
{
    private \DateTime $dueDate;

    public function __construct(
        private string $title,
        private string $description,
        private string $priority,
        ?string $dueDate
    ) {
        $date = null !== $dueDate ? \DateTime::createFromFormat(TaskDueDate::FORMAT, $dueDate) : null;
        if (false === $date) {
            throw new InvalidDateFormat(\sprintf('Invalid Due date, must be in format "%s"', TaskDueDate::FORMAT_NAME));
        }

        $this->dueDate = $date;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }
}
