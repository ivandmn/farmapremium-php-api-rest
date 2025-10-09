<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\User;

use App\Domain\Exception\InvalidEmailException;
use App\Domain\Exception\User\InvalidUserEmailException;
use App\Domain\ValueObject\Email;

final readonly class UserEmail extends Email
{
    public function __construct(private string $value)
    {
        try {
            parent::__construct(strtolower($value));
        } catch (InvalidEmailException $exception) {
            throw new InvalidUserEmailException('Invalid user email', $exception->getCode(), $exception);
        }
    }
}
