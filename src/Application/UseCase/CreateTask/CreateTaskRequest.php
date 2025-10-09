<?php

declare(strict_types = 1);

namespace App\Application\UseCase\CreateTask;

use DateTime;

final class CreateTaskRequest
{
    public function __construct(
        private string    $title,
        private ?string   $description,
        private string    $priority,
        private ?DateTime $dueDate
    ) {
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function getPriority() : string
    {
        return $this->priority;
    }

    public function getDueDate() : ?DateTime
    {
        return $this->dueDate;
    }
}
