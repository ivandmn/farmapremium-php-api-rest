<?php

declare(strict_types = 1);

namespace App\Application\UseCase\CreateTask;

use App\Infrastructure\Exception\InvalidRequestArgumentException;
use DateTimeImmutable;

final class CreateTaskRequest
{
    private const DATE_FORMAT = \DATE_ATOM;

    private DateTimeImmutable $dueDate;

    public function __construct(
        private string  $title,
        private string  $description,
        private string  $priority,
        private ?string $userId,
        ?string         $dueDate
    ) {
        $date = $dueDate !== null ? DateTimeImmutable::createFromFormat(\DATE_ATOM, $dueDate) : null;
        if ($date === false) {
            throw new InvalidRequestArgumentException(sprintf('Invalid Due date, must be in format "%s"', self::DATE_FORMAT));
        }

        $this->dueDate = $date;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function getPriority() : string
    {
        return $this->priority;
    }

    public function getUserId() : ?string
    {
        return $this->userId;
    }

    public function getDueDate() : ?DateTimeImmutable
    {
        return $this->dueDate;
    }
}
