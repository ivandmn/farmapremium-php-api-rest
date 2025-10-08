<?php

declare(strict_types=1);

namespace Domain\Exception;

use InvalidArgumentException;

class UuidInvalidException extends InvalidArgumentException
{
	public static function invalid(string $value): self
	{
		return new self(
			sprintf('Invalid UUID value "%s"', $value),
		);
	}
}