<?php

declare(strict_types = 1);

namespace App\Application\UseCase\AssignTaskToUser;

use App\Application\Service\LoggerInterface;
use App\Domain\Exception\Task\UserNotFoundException;
use App\Domain\Exception\Task\TaskNotFoundException;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Task\TaskId;
use App\Domain\ValueObject\User\UserId;

final readonly class AssignTaskToUserUserCase
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private UserRepositoryInterface $userRepository,
        private LoggerInterface         $logger
    ) {
    }

    public function __invoke(AssignTaskToUserRequest $request) : AssignTaskToUserResponse
    {
        $taskId = TaskId::fromString($request->getTaskId());
        $userId = UserId::fromString($request->getUserId());

        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new UserNotFoundException('User with this ID does not exist');
        }

        $task = $this->taskRepository->findById($taskId);

        if (!$task) {
            throw new TaskNotFoundException('Task with this ID does not exist');
        }

        $task->assignTo($user);

        $this->taskRepository->save($task);

        $this->logger->info('Task Assigned to User',
            [
                'task_id' => $task->getId()->value(),
                'user_id' => $task->getAssignedUser()->getId()->value(),
            ]
        );

        return new AssignTaskToUserResponse($taskId, $userId);
    }
}
