<?php

declare(strict_types = 1);

namespace App\Infrastructure\Http\Request\Task;

use App\Infrastructure\Http\Request\ValidationMessages;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateTaskRequestDto
{
    #[Assert\NotBlank(message: ValidationMessages::REQUIRED)]
    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public string $title;

    #[Assert\NotBlank(message: ValidationMessages::REQUIRED)]
    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $description;

    #[Assert\NotBlank(message: ValidationMessages::REQUIRED)]
    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $priority;

    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $userId;

    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $dueDate;
}
