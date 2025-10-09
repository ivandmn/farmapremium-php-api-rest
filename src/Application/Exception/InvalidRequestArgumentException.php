<?php

declare(strict_types = 1);

namespace App\Infrastructure\Exception;

use App\Domain\Exception\ApplicationException;
use Throwable;

final class InvalidRequestArgumentException extends ApplicationException
{
    private int $validationErrorCode;

    public function __construct($message = "", $code = 0, ?Throwable $previous = null, $validationErrorCode = 0)
    {
        $this->validationErrorCode = $validationErrorCode;

        parent::__construct(
            $message,
            $code,
            $previous
        );
    }

    public function getValidationErrorCode() : array
    {
        return $this->validationErrorCode;
    }
}
