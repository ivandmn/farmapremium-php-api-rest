<?php

namespace App\Infrastructure\Persistence\Doctrine\Mapper;

use App\Infrastructure\Persistence\Doctrine\Entity\Task as DoctrineTask;
use App\Domain\Model\Task as DomainTask;
use App\Domain\ValueObject\Task\TaskDescription;
use App\Domain\ValueObject\Task\TaskId;
use App\Domain\ValueObject\Task\TaskTitle;

final class TaskMapper
{
    public static function toDoctrine(DomainTask $task) : DoctrineTask
    {
        $assignedUser = $task->getAssignedUser();

        $userDoctrine = $assignedUser ? UserMapper::toDoctrine($assignedUser) : null;

        return (new DoctrineTask())
            ->setId($task->getId()->value())
            ->setTitle($task->getTitle()->value())
            ->setDescription($task->getDescription()->value())
            ->setStatus($task->getStatus())
            ->setPriority($task->getPriority())
            ->setDueDate($task->getDueDate()?->value())
            ->setAssignedTo($userDoctrine)
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
            $entity->getDueDate(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt()
        );
    }
}
