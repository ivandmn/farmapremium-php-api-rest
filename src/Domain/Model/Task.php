<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Task\TaskId;
use App\Domain\ValueObject\Task\TaskTitle;
use App\Domain\ValueObject\Task\TaskDescription;
use App\Domain\ValueObject\Task\TaskPriority;
use App\Domain\ValueObject\Task\TaskStatus;
use App\Domain\ValueObject\User\UserId;
use DateTimeImmutable;
use Domain\Exception\InvalidDomainModelArgumentException;

final class Task
{
	public function __construct(
		private TaskId $id,
		private TaskTitle $title,
		private TaskDescription $description,
		private TaskStatus $status = TaskStatus::PENDING,
		private TaskPriority $priority = TaskPriority::LOW,
		private ?UserId $assignedUserId = null,
		private ?DateTimeImmutable $dueDate = null,
		private DateTimeImmutable $createdAt = new DateTimeImmutable(),
		private ?DateTimeImmutable $updatedAt = null
	) {
	}

	public function getId(): TaskId
	{
		return $this->id;
	}
	public function getTitle(): TaskTitle
	{
		return $this->title;
	}
	public function getDescription(): TaskDescription
	{
		return $this->description;
	}
	public function getStatus(): TaskStatus
	{
		return $this->status;
	}
	public function getPriority(): TaskPriority
	{
		return $this->priority;
	}
	public function getAssignedUserId(): ?UserId
	{
		return $this->assignedUserId;
	}
	public function getDueDate(): ?DateTimeImmutable
	{
		return $this->dueDate;
	}
	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}
	public function getUpdatedAt(): DateTimeImmutable
	{
		return $this->updatedAt;
	}

	public function isCompleted(): bool
	{
		return $this->status->isCompleted();
	}

	public function isInProgress(): bool
	{
		return $this->status->isInProgress();
	}

	public function isPending(): bool
	{
		return $this->status->isPending();
	}

	public function isUnassigned(): bool
	{
		return $this->assignedUserId !== null;
	}

	public function isAssigned(): bool
	{
		return !$this->isUnassigned();
	}

	public function isOverdue(): bool
	{
		return $this->dueDate !== null
			&& $this->dueDate < new DateTimeImmutable()
			&& !$this->isCompleted();
	}

	public function isOnTime(): bool
	{
		return !$this->isOverdue();
	}

	public function changeTitle(TaskTitle $newTitle): void
	{
		if ($this->title->value() === $newTitle->value()) {
			return;
		}

		$this->title = $newTitle;
		$this->markAsUpdated();
	}

	public function changeDescription(TaskDescription $newDescription): void
	{
		if ($this->description->value() === $newDescription->value()) {
			return;
		}

		$this->description = $newDescription;
		$this->markAsUpdated();
	}

	public function changeStatus(TaskStatus $newStatus): void
	{
		if ($this->status === $newStatus) {
			return;
		}

		$this->status->ensureIsValidTransitionTo($newStatus);

		$this->status = $newStatus;
		$this->markAsUpdated();
	}

	public function changePriority(TaskPriority $newPriority): void
	{
		if ($this->priority === $newPriority) {
			return;
		}

		$this->priority = $newPriority;
		$this->markAsUpdated();
	}

	public function changeDueDate(?DateTimeImmutable $newDueDate): void
	{
		if ($newDueDate !== null && $newDueDate < new DateTimeImmutable()) {
			throw new InvalidDomainModelArgumentException('Due date cannot be in the past');
		}

		$this->dueDate = $newDueDate;
		$this->markAsUpdated();
	}

	public function assignTo(UserId $userId): void
	{
		if ($this->assignedUserId?->equals($userId)) {
			return;
		}

		$this->assignedUserId = $userId;
		$this->markAsUpdated();
	}

	public function unassign(): void
	{
		if ($this->assignedUserId === null) {
			return;
		}

		$this->assignedUserId = null;
		$this->updatedAt = new DateTimeImmutable();
	}

	private function markAsUpdated(): void
	{
		$this->updatedAt = new DateTimeImmutable();
	}
}