<?php

declare(strict_types = 1);

namespace App\Infrastructure\Http\Request\Task;

use App\Infrastructure\Http\Request\ValidationMessages;
use Symfony\Component\Validator\Constraints as Assert;

final class AssignTaskToUserDto
{
    #[Assert\NotBlank(message: ValidationMessages::REQUIRED)]
    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public string $userId;
}
