<?php

declare(strict_types=1);

namespace Domain\Exception;

use InvalidArgumentException;
use Throwable;

class InvalidDomainModelArgumentException extends InvalidArgumentException
{
	public const CODE_INVALID_DOMAIN_MODEL = 400;

	public function __construct(string $message = "", ?int $code = self::CODE_INVALID_DOMAIN_MODEL, ?Throwable $previous = null)
	{
		if ($code === null) {
			$code = self::CODE_INVALID_DOMAIN_MODEL;
		}

		parent::__construct($message, $code, $previous);
	}
}