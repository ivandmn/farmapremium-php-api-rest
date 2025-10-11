<?php

declare(strict_types = 1);

namespace App\Application\UseCase\ListTasks;

use App\Domain\Model\Task;
use ArrayIterator;

final class ListTasksResponse implements \JsonSerializable, \Countable, \IteratorAggregate
{
    public function __construct(
        private array $tasks
    ) {
    }

    public function jsonSerialize() : array
    {
        return array_map(
            static fn(Task $task) => [
                'id' => $task,
                'title' => $task->getTitle()->value(),
                'description' => $task->getDescription()->value(),
                'status' => $task->getStatus()->value,
                'priority' => $task->getPriority()->value,
                'assignedTo' => $task->assignedTo ? [
                    'id' => $task->assignedTo->getId()->value(),
                    'name' => $task->assignedTo->getName()->value(),
                ] : null,
                'dueDate' => $task->getDueDate()?->value()->format(\DATE_ATOM),
                'createdAt' => $task->getCreatedAt()->format(\DATE_ATOM),
                'updatedAt' => $task->getUpdatedAt()?->format(\DATE_ATOM),
            ], $this->tasks);
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
