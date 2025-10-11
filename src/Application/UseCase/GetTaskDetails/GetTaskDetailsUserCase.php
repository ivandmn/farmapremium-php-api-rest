<?php

declare(strict_types = 1);

namespace App\Application\UseCase\GetTaskDetails;

use App\Domain\Exception\Task\TaskNotFoundException;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\Task\TaskId;

final readonly class GetTaskDetailsUserCase
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function __invoke(GetTaskDetailsRequest $request) : GetTaskDetailsResponse
    {
        $taskId = TaskId::fromString($request->getTaskId());

        $task = $this->taskRepository->findById($taskId);

        if (!$task) {
            throw new TaskNotFoundException('Task with this ID does not exist');
        }

        return new GetTaskDetailsResponse($task);
    }
}
