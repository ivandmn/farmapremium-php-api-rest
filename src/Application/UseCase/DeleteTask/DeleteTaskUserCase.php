<?php

declare(strict_types = 1);

namespace App\Application\UseCase\DeleteTask;

use App\Application\Service\LoggerInterface;
use App\Domain\Exception\Task\TaskNotFoundException;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\Task\TaskId;

final readonly class DeleteTaskUserCase
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private LoggerInterface         $logger
    ) {
    }

    public function __invoke(DeleteTaskRequest $request) : DeleteTaskResponse
    {
        $taskId = TaskId::fromString($request->getTaskId());

        $task = $this->taskRepository->findById($taskId);

        if (!$task) {
            throw new TaskNotFoundException('Task with this ID does not exist');
        }

        $this->taskRepository->delete($task);

        $this->logger->info('Task deleted', ['task_id' => $taskId->value()]);

        return new DeleteTaskResponse($taskId);
    }
}
