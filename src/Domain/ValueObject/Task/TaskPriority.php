<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Task;

use Domain\Exception\InvalidDomainModelArgumentException;

enum TaskPriority: string
{
	case LOW = 'low';
	case MEDIUM = 'medium';
	case HIGH = 'high';

	public function isLow(): bool
	{
		return $this === self::LOW;
	}

	public function isMedium(): bool
	{
		return $this === self::MEDIUM;
	}

	public function isHigh(): bool
	{
		return $this === self::HIGH;
	}

	public function isHigherThan(TaskPriority $other): bool
	{
		return $this->getNumericValue() > $other->getNumericValue();
	}

	public function isLowerThan(TaskPriority $other): bool
	{
		return $this->getNumericValue() < $other->getNumericValue();
	}

	public function equals(TaskPriority $other): bool
	{
		return $this === $other;
	}

	public function getNumericValue(): int
	{
		return match ($this) {
			self::LOW => 1,
			self::MEDIUM => 2,
			self::HIGH => 3,
		};
	}

	public static function fromNumericValue(int $value): TaskPriority
	{
		return match ($value) {
			1 => self::LOW,
			2 => self::MEDIUM,
			3 => self::HIGH,
			default => throw new InvalidDomainModelArgumentException(
				sprintf(
					'Invalid numeric value "%d" for task priority',
					$value
				)
			),
		};
	}
}