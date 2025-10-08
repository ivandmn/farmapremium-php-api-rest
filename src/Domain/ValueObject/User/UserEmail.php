<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

use Domain\Exception\InvalidDomainModelArgumentException;

final readonly class UserEmail
{
	private const MAX_LENGTH = 320;

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

	public function equals(UserEmail $other): bool
	{
		return $this->value === $other->value;
	}

	public function getDomain(): string
	{
		return substr($this->value, strpos($this->value, '@') + 1);
	}

	public function getLocalPart(): string
	{
		return substr($this->value, 0, strpos($this->value, '@'));
	}

	public function isFromDomain(string $domain): bool
	{
		return strcasecmp($this->getDomain(), $domain) === 0;
	}

	private function normalize(string $value): string
	{
		return strtolower(trim($value));
	}

	private function ensureIsValid(string $value): void
	{
		if (empty($value)) {
			throw new InvalidDomainModelArgumentException('User email cannot be empty');
		}

		if (strlen($value) > self::MAX_LENGTH) {
			throw new InvalidDomainModelArgumentException(
				sprintf('User email cannot exceed %d characters, got %d', self::MAX_LENGTH, strlen($value))
			);
		}

		if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidDomainModelArgumentException(
				sprintf('Invalid email format "%s"', $value)
			);
		}
	}
}