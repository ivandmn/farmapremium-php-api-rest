<?php

declare(strict_types = 1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\Task as TaskDomain;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\Task\TaskId;
use App\Infrastructure\Persistence\Doctrine\Entity\Task as TaskDoctrine;
use App\Domain\ValueObject\User\UserId;
use App\Infrastructure\Persistence\Doctrine\Mapper\TaskMapper;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineTaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(TaskDomain $task) : void
    {
        $taskDoctrine = TaskMapper::toDoctrine($task);

        if ($user = $taskDoctrine->getAssignedTo()) {
            //TAKE REFERENCE FROM DOMAIN ENTITY
            $taskDoctrine->setAssignedTo(
                $this->entityManager->getReference($user::class, $user->getId())
            );
        }

        $this->entityManager->persist($taskDoctrine);
        $this->entityManager->flush();
    }

    public function delete(TaskDomain $task) : void
    {
        $this->entityManager->remove(TaskMapper::toDoctrine($task));
        $this->entityManager->flush();
    }

    public function findAll() : array
    {
        $entities = $this->entityManager->getRepository(TaskDoctrine::class)->findAll();

        return array_map([TaskMapper::class, 'toDomain'], $entities);
    }

    public function findById(TaskId $id) : ?TaskDomain
    {
        $user = $this->entityManager->getRepository(TaskDoctrine::class)->find($id->value());

        return $user ? TaskMapper::toDomain($user) : null;
    }

    public function findByUserId(UserId $userId) : array
    {
        $entities = $this->entityManager->getRepository(TaskDoctrine::class)->findBy(['assignedTo' => $userId->value()]);

        return array_map([TaskMapper::class, 'toDomain'], $entities);
    }

    public function findByFilters(array $filters, int $page, int $maxItems) : array
    {
        $offset = ($page - 1) * $maxItems;

        $entities = $this->entityManager->getRepository(TaskDoctrine::class)->findBy(
            $filters,
            ['id' => 'DESC'],
            $maxItems,
            $offset
        );

        return array_map([TaskMapper::class, 'toDomain'], $entities);
    }
}
