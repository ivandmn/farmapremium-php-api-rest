<?php

declare(strict_types = 1);

namespace App\Domain\Exception\Task;

use App\Domain\Exception\DomainException;

class TaskDueDateInPastException extends DomainException
{
}
