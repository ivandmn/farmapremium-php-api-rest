<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Domain\Exception\UuidInvalidException;

readonly class Uuid
{
	public function __construct(private string $value)
	{
		$this->ensureIsValid($value);
	}

	public static function generate(): self
	{
		return new self(RamseyUuid::uuid7()->toString());
	}

	public function value(): string
	{
		return $this->value;
	}

	public function equals(Uuid $other): bool
	{
		return $this->value === $other->value;
	}

	public function __toString(): string
	{
		return $this->value;
	}

	private function ensureIsValid(string $value): void
	{
		if (!RamseyUuid::isValid($value)) {
			throw UuidInvalidException::invalid($value);
		}
	}
}
