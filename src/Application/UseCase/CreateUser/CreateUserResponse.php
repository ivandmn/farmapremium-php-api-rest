<?php

declare(strict_types = 1);

namespace App\Application\UseCase\CreateUser;

use App\Domain\Model\User;

final class CreateUserResponse
{
    private string $id;

    private string $email;

    private string $name;

    private string $createdAt;

    public function __construct(User $user)
    {
        $this->id = $user->getId()->value();
        $this->email = $user->getEmail()->value();
        $this->name = $user->getName()->value();
        $this->createdAt = $user->getCreatedAt()->format('Y-m-d H:i:s');
    }

    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'createdAt' => $this->createdAt,
        ];
    }
}
