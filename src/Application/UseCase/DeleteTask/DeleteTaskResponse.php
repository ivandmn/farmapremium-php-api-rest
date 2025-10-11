<?php

declare(strict_types = 1);

namespace App\Application\UseCase\DeleteTask;

final class DeleteTaskResponse implements \JsonSerializable
{
    public function __construct()
    {
    }

    public function jsonSerialize() : array
    {
        return [
            'message' => 'Task deleted successfully',
        ];
    }

    public function toArray() : array
    {
        return $this->jsonSerialize();
    }
}
