<?php

declare(strict_types = 1);

namespace App\Infrastructure\Http\Request\Task;

use App\Infrastructure\Http\Request\ValidationMessages;
use Symfony\Component\Validator\Constraints as Assert;

final class ListTaskRequestDto
{
    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $status;

    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $priority;

    #[Assert\Type(type: 'int', message: ValidationMessages::TYPE)]
    #[Assert\Positive(message: ValidationMessages::POSITIVE_NUMBER)]
    public $page;

    #[Assert\Type(type: 'int', message: ValidationMessages::TYPE)]
    #[Assert\Positive(message: ValidationMessages::POSITIVE_NUMBER)]
    public $limit;
}
