<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Task;

use Domain\Exception\InvalidDomainModelArgumentException;

final readonly class TaskDescription
{
	private const MAX_LENGTH = 1000;

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

	public function isEmpty(): bool
	{
		return $this->value === '';
	}

	public function isNotEmpty(): bool
	{
		return !$this->isEmpty();
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
		if (strlen($value) > self::MAX_LENGTH) {
			throw new InvalidDomainModelArgumentException(
				sprintf(
					'Task description cannot exceed %d characters, got %d',
					self::MAX_LENGTH,
					strlen($value)
				)
			);
		}
	}
}