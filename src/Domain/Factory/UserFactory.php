<?php

namespace App\Domain\Factory;

use App\Domain\Model\User;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use DateTimeImmutable;

final class UserFactory
{
    public function register(string $email, string $name) : User
    {
        return new User(
            UserId::new(),
            UserEmail::from($email),
            UserName::from($name),
            new DateTimeImmutable('now')
        );
    }
}
