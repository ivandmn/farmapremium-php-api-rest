<?php

declare(strict_types = 1);

namespace App\Application\UseCase\ListTasks;

use App\Application\UseCase\ListUsers\ListsUserResponse;
use App\Domain\Repository\TaskRepositoryInterface;

final class ListTasksUserCase
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function __invoke(ListTasksRequest $request) : ListsUserResponse
    {
        $filters = array_filter([
            'status' => $request->getStatus(),
            'priority' => $request->getPriority(),
        ]);

        $page = $request->getPage() ?? 1;
        $limit = $request->getLimit() ?? 10;

        $users = $this->taskRepository->findByFilters($filters, $page, $limit);

        return new ListsUserResponse($users);
    }
}
