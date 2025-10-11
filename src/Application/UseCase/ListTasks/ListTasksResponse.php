<?php

declare(strict_types = 1);

namespace App\Application\UseCase\ListTasks;

use App\Domain\Model\Task;
use ArrayIterator;

final class ListTasksResponse implements \JsonSerializable, \Countable, \IteratorAggregate
{
    public function __construct(
        private array $tasks,
        private ?int $page = null,
        private ?int $limit = null,
    ) {
    }

    public function jsonSerialize() : array
    {
        return [
            'data' => array_map(
                static fn(Task $task) => [
                    'id' => $task->getId()->value(),
                    'title' => $task->getTitle()->value(),
                    'description' => $task->getDescription()->value(),
                    'status' => $task->getStatus()->value,
                    'priority' => $task->getPriority()->value,
                    'assignedTo' => $task->getAssignedUser() ? [
                        'id' => $task->getAssignedUser()->getId()->value(),
                        'name' => $task->getAssignedUser()->getName()->value(),
                    ] : null,
                    'dueDate' => $task->getDueDate()?->value()->format(\DATE_ATOM),
                    'createdAt' => $task->getCreatedAt()->format(\DATE_ATOM),
                    'updatedAt' => $task->getUpdatedAt()?->format(\DATE_ATOM),
                ],
                $this->tasks
            ),
            'meta' => [
                'total' => $this->count(),
                'page' => $this->page ?? 1,
                'limit' => $this->limit ?? $this->count(),
            ],
        ];
    }


    public function count() : int
    {
        return count($this->tasks);
    }

    public function getIterator() : \Traversable
    {
        return new ArrayIterator($this->tasks);
    }

    public function isEmpty() : bool
    {
        return $this->tasks === [];
    }
}
