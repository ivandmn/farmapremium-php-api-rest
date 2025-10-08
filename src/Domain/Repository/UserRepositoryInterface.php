<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Model\User;

interface UserRepositoryInterface
{
    public function findById(string $id) : ?User;

    public function findByEmail(string $email) : ?User;

    public function findAll() : array;

    public function save(User $user) : void;

    public function delete(User $user) : void;
}
