<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Task;

use Domain\Exception\InvalidDomainModelArgumentException;

final readonly class TaskTitle
{
	private const MAX_LENGTH = 255;

	public function __construct(private string $value)
	{
		$normalizedValue = $this->normalize($value);
		$this->ensureIsValid($normalizedValue);
		$this->value = $normalizedValue;
	}

	public function value(): string
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return $this->value;
	}

	private function normalize(string $value): string
	{
		return trim($value);
	}

	private function ensureIsValid(string $value): void
	{
		if (empty($value)) {
			throw new InvalidDomainModelArgumentException(
				'Task title cannot be empty'
			);
		}

		if (strlen($value) > self::MAX_LENGTH) {
			throw new InvalidDomainModelArgumentException(
				sprintf(
					'Task title cannot exceed %d characters, got %d',
					self::MAX_LENGTH,
					strlen($value)
				)
			);
		}
	}
}