<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Request\Task;

use App\Infrastructure\Http\Request\ValidationMessages;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateTaskRequestDto
{
    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $title;

    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $description;

    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $status;

    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $priority;

    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $dueDate;
}
