<?php

declare(strict_types = 1);

namespace App\Application\UseCase\CreateTask;

use App\Domain\Exception\Task\AssignedUserDoesNotExistException;
use App\Domain\Factory\TaskFactory;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Task\TaskDescription;
use App\Domain\ValueObject\Task\TaskDueDate;
use App\Domain\ValueObject\Task\TaskPriority;
use App\Domain\ValueObject\Task\TaskTitle;
use App\Domain\ValueObject\User\UserId;

final class CreateTaskUserCase
{
    public function __construct(
        private TaskFactory             $taskFactory,
        private TaskRepositoryInterface $taskRepository,
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(CreateTaskRequest $request) : CreateTaskResponse
    {
        $title = TaskTitle::fromString($request->getTitle());
        $description = TaskDescription::fromString($request->getDescription());
        $priority = TaskPriority::fromString($request->getPriority());
        $dueDate = $request->getDueDate() ? TaskDueDate::fromDate($request->getDueDate()) : null;
        $userId = $request->getUserId() ? UserId::fromString($request->getUserId()) : null;

        if ($userId) {
            $user = $this->userRepository->findById($userId);

            if (!$user) {
                throw new AssignedUserDoesNotExistException('User with this ID does not exist');
            }
        }

        $task = $this->taskFactory->register(
            $title,
            $description,
            $priority,
            $dueDate,
            $user ?? null
        );

        $this->taskRepository->save($task);

        return new CreateTaskResponse($task);
    }
}
