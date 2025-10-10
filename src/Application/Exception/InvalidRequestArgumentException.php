<?php

declare(strict_types = 1);

namespace App\Infrastructure\Exception;

use App\Domain\Exception\ApplicationException;

final class InvalidRequestArgumentException extends ApplicationException
{
}
