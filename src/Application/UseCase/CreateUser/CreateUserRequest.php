<?php

declare(strict_types = 1);

namespace App\Application\UseCase\CreateUser;

use App\Infrastructure\Exception\InvalidRequestArgumentException;

final class CreateUserRequest
{
    public function __construct(
        private string $email,
        private string $name
    ) {
        if(trim($email) === '') {
            throw new InvalidRequestArgumentException('Email cannot be empty');
        }

        if(trim($email) === '') {
            throw new InvalidRequestArgumentException('Name cannot be empty');
        }
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getName() : string
    {
        return $this->name;
    }
}
