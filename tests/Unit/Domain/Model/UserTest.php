<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Domain\Model;

use App\Domain\Exception\User\InvalidUserEmailException;
use App\Domain\Exception\User\InvalidUserIdException;
use App\Domain\Exception\User\InvalidUserNameException;
use App\Domain\Model\User;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function test_user_is_created_correctly() : void
    {
        $id = new UserId('123e4567-e89b-12d3-a456-426614174000');
        $email = new UserEmail('test@example.com');
        $name = new UserName('Test User');
        $created_at = new DateTimeImmutable('2025-01-01 10:00:00');

        $user = new User($id, $email, $name, $created_at);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame($id, $user->getId());
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($name, $user->getName());
        $this->assertSame($created_at, $user->getCreatedAt());
    }

    public function test_user_created_at_defaults_to_now() : void
    {
        $id = new UserId('123e4567-e89b-12d3-a456-426614174000');
        $email = new UserEmail('test@example.com');
        $name = new UserName('Test User');

        $before = new DateTimeImmutable();
        $user = new User($id, $email, $name);
        $after = new DateTimeImmutable();

        $created_at = $user->getCreatedAt();
        $this->assertInstanceOf(DateTimeImmutable::class, $created_at);
        $this->assertGreaterThanOrEqual($before->getTimestamp(), $created_at->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $created_at->getTimestamp());
        $this->assertLessThanOrEqual(2, abs($after->getTimestamp() - $created_at->getTimestamp()));
    }

    public function test_user_id_invalid_throws_exception() : void
    {
        $this->expectException(InvalidUserIdException::class);
        $this->expectExceptionMessage('Invalid User Id');

        new UserId('not-a-uuid');
    }

    public function test_user_email_invalid_throws_exception() : void
    {
        $this->expectException(InvalidUserEmailException::class);
        $this->expectExceptionMessage('Invalid user email');

        new UserEmail('not-an-email');
    }

    public function test_user_email_max_length_throws_exception() : void
    {
        $this->expectException(InvalidUserEmailException::class);
        $this->expectExceptionMessage('Invalid user email');

        $localPart = str_repeat('a', 246);
        $longEmail = 'b' . $localPart . '@ex.com';
        $longEmail = str_pad($longEmail, 256, 'c');
        new UserEmail($longEmail);
    }

    public function test_user_name_empty_throws_exception() : void
    {
        $this->expectException(InvalidUserNameException::class);
        $this->expectExceptionMessage('User name cannot be empty');

        new UserName('');
    }

    public function test_user_name_max_length_throws_exception() : void
    {
        $this->expectException(InvalidUserNameException::class);
        $this->expectExceptionMessage('User name exceeds maximum characters length');

        $longName = str_repeat('a', UserName::MAX_LENGTH + 1);
        new UserName($longName);
    }
}
