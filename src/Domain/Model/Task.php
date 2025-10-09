<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\Exception\Task\InvalidTaskStatusTransitionException;
use App\Domain\ValueObject\Task\TaskDescription;
use App\Domain\ValueObject\Task\TaskDueDate;
use App\Domain\ValueObject\Task\TaskId;
use App\Domain\ValueObject\Task\TaskPriority;
use App\Domain\ValueObject\Task\TaskStatus;
use App\Domain\ValueObject\Task\TaskTitle;
use App\Domain\ValueObject\Uuid;
use DateTime;
use DateTimeImmutable;

final class Task
{
    private bool $isUpdated = false;

    public function __construct(
        private readonly TaskId            $id,
        private TaskTitle                  $title,
        private TaskDescription            $description,
        private TaskStatus                 $status = TaskStatus::PENDING,
        private TaskPriority               $priority = TaskPriority::LOW,
        private ?User                      $assignedUser = null,
        private ?TaskDueDate               $dueDate = null,
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        private ?DateTime                  $updatedAt = null
    ) {
    }

    public function getId() : Uuid
    {
        return $this->id;
    }

    public function getTitle() : TaskTitle
    {
        return $this->title;
    }

    public function getDescription() : TaskDescription
    {
        return $this->description;
    }

    public function getStatus() : TaskStatus
    {
        return $this->status;
    }

    public function getPriority() : TaskPriority
    {
        return $this->priority;
    }

    public function getAssignedUser() : ?User
    {
        return $this->assignedUser;
    }

    public function getDueDate() : ?TaskDueDate
    {
        return $this->dueDate;
    }

    public function getCreatedAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt() : ?DateTime
    {
        return $this->updatedAt;
    }

    public function changeTitle(TaskTitle $newTitle) : void
    {
        if ($this->title->equals($newTitle)) {
            return;
        }

        $this->title = $newTitle;
        $this->markAsUpdated();
    }

    public function changeDescription(TaskDescription $newDescription) : void
    {
        if ($this->description->equals($newDescription)) {
            return;
        }

        $this->description = $newDescription;
        $this->markAsUpdated();
    }

    public function changeStatus(TaskStatus $newStatus) : void
    {
        if ($this->status->equals($newStatus)) {
            return;
        }

        if (!$this->status->canTransitionTo($newStatus)) {
            throw new InvalidTaskStatusTransitionException(
                sprintf(
                    'Status transition from %s to %s is not allowed',
                    $this->status->value,
                    $newStatus->value
                )
            );
        }

        $this->status = $newStatus;
        $this->markAsUpdated();
    }

    public function changePriority(TaskPriority $newPriority) : void
    {
        if ($this->priority->equals($newPriority)) {
            return;
        }

        $this->priority = $newPriority;
        $this->markAsUpdated();
    }

    public function assignTo(User $user) : void
    {
        if ($this->assignedUser?->equals($user)) {
            return;
        }
        $this->assignedUser = $user;
        $this->markAsUpdated();
    }

    public function unassign() : void
    {
        if ($this->assignedUser === null) {
            return;
        }

        $this->assignedUser = null;
        $this->markAsUpdated();
    }

    public function changeDueDate(?DateTimeImmutable $taskDueDate) : void
    {
        $this->dueDate = $taskDueDate ? new TaskDueDate($taskDueDate) : null;
        $this->markAsUpdated();
    }

    private function markAsUpdated() : void
    {
        $this->isUpdated = true;
        $this->updatedAt = new DateTime('now');
    }

    public function isUpdated() : bool
    {
        return $this->isUpdated;
    }

    public function isCompleted() : bool
    {
        return $this->status->isCompleted();
    }

    public function isInProgress() : bool
    {
        return $this->status->isInProgress();
    }

    public function isPending() : bool
    {
        return $this->status->isPending();
    }

    public function isAssigned() : bool
    {
        return !$this->isUnassigned();
    }

    public function isUnassigned() : bool
    {
        return $this->assignedUser !== null;
    }

    public function isOverdue() : bool
    {
        return $this->dueDate !== null
            && $this->dueDate->value() < new DateTimeImmutable('now')
            && !$this->isCompleted();
    }

    public function isOnTime() : bool
    {
        return !$this->isOverdue();
    }

    public function canBeDeleted() : bool
    {
        return $this->status->isPending();
    }
}
