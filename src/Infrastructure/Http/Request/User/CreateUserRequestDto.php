<?php

declare(strict_types = 1);

namespace App\Infrastructure\Http\Request\User;

use App\Infrastructure\Http\Request\ValidationMessages;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateUserRequestDto
{
    #[Assert\NotBlank(message: ValidationMessages::REQUIRED)]
    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $email;

    #[Assert\NotBlank(message: ValidationMessages::REQUIRED)]
    #[Assert\Type(type: 'string', message: ValidationMessages::TYPE)]
    public $name;
}
