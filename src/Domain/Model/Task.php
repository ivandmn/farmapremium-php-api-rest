<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\Exception\InvalidArgumentException;
use App\Domain\Exception\Task\AssignedUserDoesNotExistException;
use App\Domain\Exception\Task\InvalidTaskStatusTransitionException;
use App\Domain\ValueObject\Task\TaskDescription;
use App\Domain\ValueObject\Task\TaskId;
use App\Domain\ValueObject\Task\TaskPriority;
use App\Domain\ValueObject\Task\TaskStatus;
use App\Domain\ValueObject\Task\TaskTitle;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\Uuid;
use DateTime;
use DateTimeImmutable;

final class Task
{
    public function __construct(
        private readonly TaskId            $id,
        private TaskTitle                  $title,
        private TaskDescription            $description,
        private TaskStatus                 $status = TaskStatus::PENDING,
        private TaskPriority               $priority = TaskPriority::LOW,
        private ?UserId                    $assignedUserId = null,
        private ?DateTime                  $dueDate = null,
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        private ?DateTime                  $updatedAt = null,
        ?callable                          $userExistsChecker = null
    ) {
        $this->assertDueDateIsValid($this->dueDate);
        $this->assertAssignedUserExists($assignedUserId, $userExistsChecker);
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

    public function getAssignedUserId() : ?UserId
    {
        return $this->assignedUserId;
    }

    public function getDueDate() : ?DateTime
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

    public function isUnassigned() : bool
    {
        return $this->assignedUserId !== null;
    }

    public function isAssigned() : bool
    {
        return !$this->isUnassigned();
    }

    public function isOverdue() : bool
    {
        return $this->dueDate !== null
            && $this->dueDate < new DateTime()
            && !$this->isCompleted();
    }

    public function isOnTime() : bool
    {
        return !$this->isOverdue();
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

    public function assignTo(UserId $userId, ?callable $userExistsChecker = null) : void
    {
        if ($this->assignedUserId?->equals($userId)) {
            return;
        }

        if ($userExistsChecker !== null && !$userExistsChecker($userId)) {
            throw new AssignedUserDoesNotExistException('Assigned user does not exist');
        }

        $this->assignedUserId = $userId;
        $this->markAsUpdated();
    }

    public function unassign() : void
    {
        if ($this->assignedUserId === null) {
            return;
        }

        $this->assignedUserId = null;
        $this->markAsUpdated();
    }

    public function changeDueDate(?DateTime $newDueDate) : void
    {
        if ($newDueDate !== null && $newDueDate < new DateTime()) {
            throw new InvalidArgumentException('Due date cannot be in the past');
        }

        $this->dueDate = $newDueDate;
        $this->markAsUpdated();
    }

    public function canBeDeleted() : bool
    {
        return $this->status->isPending();
    }

    private function assertDueDateIsValid(?DateTime $dueDate) : void
    {
        if ($dueDate !== null && $dueDate < new DateTime()) {
            throw new InvalidArgumentException('Due date cannot be in the past');
        }
    }

    private function assertAssignedUserExists(?Uuid $userId, ?callable $userExistsChecker) : void
    {
        if ($userId !== null && $userExistsChecker !== null && !$userExistsChecker($userId)) {
            throw new InvalidArgumentException('Assigned user does not exist');
        }
    }

    private function markAsUpdated() : void
    {
        $this->updatedAt = new DateTime();
    }
}
