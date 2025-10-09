<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use DateTimeImmutable;

final class User
{
    public function __construct(
        private readonly UserId            $id,
        private UserEmail                  $email,
        private UserName                   $name,
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
    }

    public function getId() : UserId
    {
        return $this->id;
    }

    public function getEmail() : Email
    {
        return $this->email;
    }

    public function getName() : UserName
    {
        return $this->name;
    }

    public function getCreatedAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function changeEmail(UserEmail $newEmail) : void
    {
        if ($this->email->equals($newEmail)) {
            return;
        }

        $this->email = $newEmail;
    }

    public function changeName(UserName $newName) : void
    {
        if ($this->name->equals($newName)) {
            return;
        }

        $this->name = $newName;
    }
}
