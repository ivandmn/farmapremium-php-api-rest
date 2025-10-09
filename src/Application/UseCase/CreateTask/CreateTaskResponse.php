<?php

declare(strict_types = 1);

namespace App\Application\UseCase\CreateTask;

use App\Domain\Model\User;

final class CreateTaskResponse implements \JsonSerializable
{
    private string $id;

    private string $title;

    private string $description;

    private string $status;

    private string $priority;
    private ?User $assignedTo;

    private ?DateTime $dueDate;

    private DateTimeImmutable $createdAt;

    private ?DateTime $updatedAt;

    public function __construct(Task $task)
    {
        $this->id = $task->getId()->value();
        $this->title = $task->getTitle()->value();
        $this->description = $task->getDescription()->value();
        $this->status = $task->getStatus()->value();
        $this->assignedTo = $task->get()->format(\DATE_ATOM);
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'createdAt' => $this->createdAt,
        ];
    }

    public function toArray() : array
    {
        return $this->jsonSerialize();
    }
}
