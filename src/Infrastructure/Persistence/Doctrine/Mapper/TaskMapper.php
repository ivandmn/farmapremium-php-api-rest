<?php

namespace App\Infrastructure\Persistence\Doctrine\Mapper;

use App\Domain\ValueObject\Task\TaskDueDate;
use App\Infrastructure\Persistence\Doctrine\Entity\Task as DoctrineTask;
use App\Infrastructure\Persistence\Doctrine\Entity\User as DoctrineUser;
use App\Domain\Model\Task as DomainTask;
use App\Domain\ValueObject\Task\TaskDescription;
use App\Domain\ValueObject\Task\TaskId;
use App\Domain\ValueObject\Task\TaskTitle;

final class TaskMapper
{
    public static function toDoctrine(
        DomainTask    $task,
        ?DoctrineTask $taskRef = null,
        ?DoctrineUser $userRef = null
    ) : DoctrineTask {
        $taskDoctrine = $taskRef ?? new DoctrineTask();

        return $taskDoctrine
            ->setId($task->getId()->value())
            ->setTitle($task->getTitle()->value())
            ->setDescription($task->getDescription()->value())
            ->setStatus($task->getStatus())
            ->setPriority($task->getPriority())
            ->setDueDate($task->getDueDate()?->value())
            ->setAssignedTo($userRef)
            ->setCreatedAt($task->getCreatedAt())
            ->setUpdatedAt($task->getUpdatedAt());
    }

    public static function toDomain(DoctrineTask $entity) : DomainTask
    {
        $doctrineAssignedUser = $entity->getAssignedTo();

        $userDomain = $doctrineAssignedUser ? UserMapper::toDomain($doctrineAssignedUser) : null;

        return new DomainTask(
            TaskId::fromString($entity->getId()),
            TaskTitle::fromString($entity->getTitle()),
            TaskDescription::fromString($entity->getDescription()),
            $entity->getStatus(),
            $entity->getPriority(),
            $userDomain,
            $entity->getDueDate() ? TaskDueDate::fromDate($entity->getDueDate()) : null,
            $entity->getCreatedAt(),
            $entity->getUpdatedAt()
        );
    }
}
