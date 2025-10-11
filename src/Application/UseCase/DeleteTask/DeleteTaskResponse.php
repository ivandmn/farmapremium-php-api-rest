<?php

declare(strict_types = 1);

namespace App\Application\UseCase\DeleteTask;

use App\Domain\ValueObject\Task\TaskId;

final readonly class DeleteTaskResponse implements \JsonSerializable
{
    public function __construct(private TaskId $taskId)
    {
    }

    public function jsonSerialize() : array
    {
        return [
            'message' => sprintf('Task "%s" has been successfully deleted.', $this->taskId->value()),
        ];
    }

    public function toArray() : array
    {
        return $this->jsonSerialize();
    }
}
