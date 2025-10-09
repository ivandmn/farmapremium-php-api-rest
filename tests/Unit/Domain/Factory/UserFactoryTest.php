<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Domain\Factory;

use App\Domain\Factory\UserFactory;
use App\Domain\Model\User;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

final class UserFactoryTest extends TestCase
{
    public function test_register_creates_a_valid_user() : void
    {
        $email = UserEmail::fromString('test@example.com');
        $name = UserName::fromString('Test User');
        $before = new DateTimeImmutable('now');

        $user = (new UserFactory())->register($email, $name);

        $after = new DateTimeImmutable('now');

        $createdAt = $user->getCreatedAt();

        $this->assertInstanceOf(User::class, $user);
        $this->assertInstanceOf(UserId::class, $user->getId());
        $this->assertInstanceOf(UserEmail::class, $user->getEmail());
        $this->assertInstanceOf(UserName::class, $user->getName());
        $this->assertInstanceOf(DateTimeImmutable::class, $createdAt);
        $this->assertGreaterThanOrEqual($before->getTimestamp(), $createdAt->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $createdAt->getTimestamp());
        $this->assertLessThanOrEqual(2, abs($after->getTimestamp() - $createdAt->getTimestamp()));
        $this->assertSame($email->value(), $user->getEmail()->value());
        $this->assertSame($name->value(), $user->getName()->value());
    }
}
