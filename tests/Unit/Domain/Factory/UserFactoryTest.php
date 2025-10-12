<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Domain\Factory;

use App\Domain\Factory\UserFactory;
use App\Domain\Model\User;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class UserFactoryTest extends TestCase
{
    public function test_register_creates_a_valid_user() : void
    {
        $class = new UserFactory();

        $email = UserEmail::fromString('test@example.com');
        $name = UserName::fromString('Test User');

        $before = new DateTimeImmutable('now');
        $user = $class->register($email, $name);
        $after = new DateTimeImmutable('now');

        $this->assertInstanceOf(User::class, $user);
        $this->assertInstanceOf(UserId::class, $user->getId());
        $this->assertInstanceOf(UserEmail::class, $user->getEmail());
        $this->assertInstanceOf(UserName::class, $user->getName());
        $this->assertInstanceOf(DateTimeImmutable::class, $user->getCreatedAt());

        $this->assertGreaterThanOrEqual($before->getTimestamp(), $user->getCreatedAt()->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $user->getCreatedAt()->getTimestamp());

        $this->assertSame($email->value(), $user->getEmail()->value());
        $this->assertSame($name->value(), $user->getName()->value());
    }
}
