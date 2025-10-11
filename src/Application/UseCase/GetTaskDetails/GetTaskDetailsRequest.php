<?php

declare(strict_types = 1);

namespace App\Application\UseCase\GetTaskDetails;

final readonly class GetTaskDetailsRequest
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
