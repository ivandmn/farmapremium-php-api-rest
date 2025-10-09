<?php

declare(strict_types = 1);

namespace App\Application\UseCase\CreateTask;

use App\Application\UseCase\CreateTask\CreateTaskRequest;
use App\Domain\Factory\TaskFactory;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\Task\TaskDescription;
use App\Domain\ValueObject\Task\TaskDueDate;
use App\Domain\ValueObject\Task\TaskPriority;
use App\Domain\ValueObject\Task\TaskTitle;

final class CreateTaskUserCase
{
    public function __construct(
        private TaskFactory             $taskFactory,
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function __invoke(CreateTaskRequest $request) : CreateTaskResponse
    {
        $title = TaskTitle::fromString($request->getTitle());
        $description = TaskDescription::fromString($request->getDescription());
        $priority = TaskPriority::fromString($request->getPriority());
        $dueDate = $request->getDueDate() ? TaskDueDate::fromDate($request->getDueDate()) : null;

        $task = $this->taskFactory->register(
            $title,
            $description,
            $priority,
            $dueDate
        );

        $this->taskRepository->save($task);

        return new CreateTaskResponse($task);
    }
}
