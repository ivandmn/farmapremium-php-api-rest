<?php

namespace App\Domain\Factory;

use App\Domain\Model\User;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use DateTimeImmutable;

final class UserFactory
{
    public function register(UserEmail $email, UserName $name) : User
    {
        return new User(
            UserId::new(),
            $email,
            $name,
            new DateTimeImmutable('now')
        );
    }
}
