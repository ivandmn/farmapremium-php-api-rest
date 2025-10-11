<?php

declare(strict_types = 1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\Task as TaskDomain;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\Task\TaskId;
use App\Infrastructure\Persistence\Doctrine\Entity\Task;
use App\Domain\ValueObject\User\UserId;
use App\Infrastructure\Persistence\Doctrine\Entity\User;
use App\Infrastructure\Persistence\Doctrine\Mapper\TaskMapper;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineTaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function create(TaskDomain $task) : void
    {
        if ($user = $task->getAssignedUser()) {
            //TAKE REFERENCE FROM DOMAIN ENTITY
            $userRef = $this->entityManager->getReference(\App\Infrastructure\Persistence\Doctrine\Entity\User::class, $user->getId());
        }

        $taskDoctrine = TaskMapper::toDoctrine($task, null, $userRef ?? null);

        $this->entityManager->persist($taskDoctrine);
        $this->entityManager->flush();
    }

    public function update(TaskDomain $task) : void
    {
        $taskRef = $this->entityManager->getReference(\App\Infrastructure\Persistence\Doctrine\Entity\Task::class, $task->getId()->value());

        if ($user = $task->getAssignedUser()) {
            //TAKE REFERENCE FROM DOMAIN ENTITY
            $userRef = $this->entityManager->getReference(\App\Infrastructure\Persistence\Doctrine\Entity\User::class, $user->getId());
        }

        $taskDoctrine = TaskMapper::toDoctrine($task, $taskRef, $userRef ?? null);

        $this->entityManager->persist($taskDoctrine);
        $this->entityManager->flush();
    }

    public function delete(TaskDomain $task) : void
    {
        $taskDoctrine = TaskMapper::toDoctrine($task);

        //TAKE REFERENCE FROM TASK ENTITY
        $taskDoctrine = $this->entityManager->getReference($taskDoctrine::class, $taskDoctrine->getId());

        $this->entityManager->remove($taskDoctrine);
        $this->entityManager->flush();
    }

    public function findAll() : array
    {
        $entities = $this->entityManager->getRepository(Task::class)->findAll();

        return array_map([TaskMapper::class, 'toDomain'], $entities);
    }

    public function findById(TaskId $id) : ?TaskDomain
    {
        $user = $this->entityManager->getRepository(Task::class)->find($id->value());

        return $user ? TaskMapper::toDomain($user) : null;
    }

    public function findByUserId(UserId $userId) : array
    {
        $entities = $this->entityManager->getRepository(Task::class)->findBy(['assignedTo' => $userId->value()]);

        return array_map([TaskMapper::class, 'toDomain'], $entities);
    }

    public function findByFilters(array $filters, int $page, int $maxItems) : array
    {
        $offset = ($page - 1) * $maxItems;

        $entities = $this->entityManager->getRepository(Task::class)->findBy(
            $filters,
            ['id' => 'DESC'],
            $maxItems,
            $offset
        );

        return array_map([TaskMapper::class, 'toDomain'], $entities);
    }
}
