<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\Task;

use App\Domain\Exception\InvalidUuidException;
use App\Domain\Exception\Task\InvalidTaskIdException;
use App\Domain\ValueObject\Uuid;

final readonly class TaskId extends Uuid
{
    public function __construct(private string $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidUuidException $exception) {
            throw new InvalidTaskIdException('Invalid Task Id', $exception->getCode(), $exception);
        }
    }
}
