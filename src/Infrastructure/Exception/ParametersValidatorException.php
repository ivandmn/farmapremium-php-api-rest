<?php

declare(strict_types = 1);

namespace App\Infrastructure\Exception;

use App\Domain\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ParametersValidatorException extends InvalidArgumentException
{
    public const CODE_UNKNOWN = 'unknown';
    public const CODE_NOT_ALLOWED_PARAMETER = 'not_allowed';
    public const CODE_MISSING_PARAMETER = 'missing';
    private const ERRORS = [
        self::CODE_NOT_ALLOWED_PARAMETER => 'The parameter "%s" is not allowed.',
        self::CODE_MISSING_PARAMETER => 'The parameter "%s" is required.',
    ];

    public function __construct(
        private readonly array $errors,
        string                 $message = 'Validation failed',
        int                    $code = Response::HTTP_BAD_REQUEST,
        ?Throwable             $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function getFirstError() : array
    {
        [$parameter, $code] = reset($this->errors);

        return ['parameter' => $parameter, 'code' => $code, 'message' => self::ERRORS[$code] ?? self::CODE_UNKNOWN];
    }

    public function getErrorParameter(array $error) : string
    {
        [$parameter] = $error;

        return $parameter;
    }

    public function getErrorCode(array $error) : string
    {
        [, $code] = reset($error);

        return $code;
    }

    public function getErrorMessage(array $error) : string
    {
        [, $code] = reset($error);

        return self::ERRORS[$code] ?? self::CODE_UNKNOWN;
    }
}
