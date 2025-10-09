<?php

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use App\Domain\ValueObject\Task\TaskDescription;
use App\Domain\ValueObject\Task\TaskId;
use App\Domain\ValueObject\Task\TaskPriority;
use App\Domain\ValueObject\Task\TaskStatus;
use App\Domain\ValueObject\Task\TaskTitle;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task')]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: TaskId::LENGTH, unique: true, options: ['fixed' => true])]
    private string $id;

    #[ORM\Column(type: 'string', length: TaskTitle::MAX_LENGTH, nullable: false)]
    private string $title;

    #[ORM\Column(type: 'string', length: TaskDescription::MAX_LENGTH, nullable: false)]
    private string $description;

    #[ORM\Column(nullable: false, enumType: TaskStatus::class)]
    private TaskStatus $status;

    #[ORM\Column(nullable: false, enumType: TaskPriority::class)]
    private TaskPriority $priority;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?User $user;

    #[ORM\Column(name: 'due_date', type: 'datetime', nullable: true)]
    private ?DateTime $dueDate;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true)]
    private ?DateTime $updatedAt;

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description) : Task
    {
        $this->description = $description;

        return $this;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : Task
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function setTitle(string $title) : Task
    {
        $this->title = $title;

        return $this;
    }

    public function getPriority() : TaskPriority
    {
        return $this->priority;
    }

    public function setPriority(TaskPriority $priority) : Task
    {
        $this->priority = $priority;

        return $this;
    }

    public function getStatus() : TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status) : Task
    {
        $this->status = $status;

        return $this;
    }

    public function getUser() : ?User
    {
        return $this->user;
    }

    public function setUser(?User $user) : Task
    {
        $this->user = $user;

        return $this;
    }

    public function getDueDate() : ?DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(?DateTime $dueDate) : Task
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getCreatedAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt) : Task
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt() : ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt) : Task
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
