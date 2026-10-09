<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject\User;

use App\Domain\Exception\User\InvalidUserNameException;
use App\Domain\ValueObject\User\UserName;
use PHPUnit\Framework\TestCase;

final class UserNameTest extends TestCase
{
    public function test_creates_valid_user_name(): void
    {
        $name = new UserName('Valid Name');

        $this->assertInstanceOf(UserName::class, $name);
        $this->assertSame('Valid Name', $name->value());
        $this->assertSame('Valid Name', (string) $name);
    }

    public function test_from_string_creates_valid_user_name(): void
    {
        $name = UserName::fromString('Another Name');

        $this->assertInstanceOf(UserName::class, $name);
        $this->assertSame('Another Name', $name->value());
    }

    public function test_throws_exception_when_too_short(): void
    {
        $this->expectException(InvalidUserNameException::class);
        $this->expectExceptionMessage('User name does not reach minimum characters length');

        new UserName('abc');
    }

    public function test_throws_exception_when_too_long(): void
    {
        $this->expectException(InvalidUserNameException::class);
        $this->expectExceptionMessage('User name exceeds maximum characters length');

        new UserName(\str_repeat('a', UserName::MAX_LENGTH + 1));
    }

    public function test_equals_returns_true_for_same_value(): void
    {
        $a = new UserName('Same Name');
        $b = new UserName('Same Name');

        $this->assertTrue($a->equals($b));
    }

    public function test_equals_returns_false_for_different_value(): void
    {
        $a = new UserName('Name One');
        $b = new UserName('Name Two');

        $this->assertFalse($a->equals($b));
    }
}
