<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\User;

use App\Domain\Exception\InvalidUuidException;
use App\Domain\Exception\User\InvalidUserIdException;
use App\Domain\ValueObject\Uuid;

final readonly class UserId extends Uuid
{
    public function __construct(private string $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidUuidException $exception) {
            throw new InvalidUserIdException('Invalid User Id', $exception->getCode(), $exception);
        }
    }
}
