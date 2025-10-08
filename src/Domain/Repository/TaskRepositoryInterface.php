<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Task;

interface TaskRepositoryInterface
{
    public function findById(string $id): ?Task;

    public function findAll(): array;

    public function save(Task $task): void;

    public function delete(Task $task): void;

    public function findByUserId(string $userId): array;
}
