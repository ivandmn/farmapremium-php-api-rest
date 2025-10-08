<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\User;

use App\Domain\ValueObject\Uuid;
use Domain\Exception\InvalidDomainModelArgumentException;
use Domain\Exception\UuidInvalidException;

final readonly class UserId extends Uuid
{
	public static function generate(): self
	{
		return new self(parent::generate()->value());
	}

	public static function fromString(string $value): self
	{
		try {
			return new self($value);
		} catch (UuidInvalidException $exception) {
			throw new InvalidDomainModelArgumentException("Invalid User ID",InvalidDomainModelArgumentException::CODE_INVALID_DOMAIN_MODEL, $exception);
		}
	}
}