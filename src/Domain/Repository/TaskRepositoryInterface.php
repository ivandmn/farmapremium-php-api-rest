<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Model\Task;
use App\Domain\ValueObject\Task\TaskId;
use App\Domain\ValueObject\User\UserId;

interface TaskRepositoryInterface
{
    public function save(Task $task) : void;

    public function delete(Task $task) : void;

    public function findAll() : array;

    public function findById(TaskId $id) : ?Task;

    public function findByUserId(UserId $userId) : ?Task;
}
