<?php

declare(strict_types = 1);

namespace App\Application\UseCase\AssignTaskToUser;

use App\Domain\ValueObject\Task\TaskId;
use App\Domain\ValueObject\User\UserId;

final readonly class AssignTaskToUserResponse implements \JsonSerializable
{
    public function __construct(
        private TaskId $taskId,
        private UserId $userId
    ) {
    }

    public function jsonSerialize() : array
    {
        return [
            'message' => sprintf('Task "%s" has been assigned to "%s".', $this->taskId->value(), $this->userId->value()),
        ];
    }

    public function toArray() : array
    {
        return $this->jsonSerialize();
    }
}
