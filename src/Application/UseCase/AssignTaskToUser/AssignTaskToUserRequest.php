<?php

declare(strict_types = 1);

namespace App\Application\UseCase\AssignTaskToUser;

final readonly class AssignTaskToUserRequest
{
    public function __construct(
        private string $taskId,
        private string $userId
    ) {
    }

    public function getTaskId() : string
    {
        return $this->taskId;
    }

    public function getUserId() : string
    {
        return $this->userId;
    }
}
