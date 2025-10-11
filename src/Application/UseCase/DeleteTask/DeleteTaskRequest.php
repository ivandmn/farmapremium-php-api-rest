<?php

declare(strict_types = 1);

namespace App\Application\UseCase\DeleteTask;

final readonly class DeleteTaskRequest
{
    public function __construct(
        private string $taskId
    ) {
    }

    public function getTaskId() : string
    {
        return $this->taskId;
    }
}
