<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use DateTimeImmutable;

final readonly class User
{
    public function __construct(
        private UserId            $id,
        private UserEmail         $email,
        private UserName          $name,
        private DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
    }

    public function getId() : UserId
    {
        return $this->id;
    }

    public function getEmail() : UserEmail
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

    public function equals(self $other): bool
    {
        return $this->id->equals($other->id);
    }
}
