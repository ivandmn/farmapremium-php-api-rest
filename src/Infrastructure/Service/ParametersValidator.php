<?php

declare(strict_types = 1);

namespace App\Infrastructure\Service;

use App\Infrastructure\Exception\ParametersValidatorException;

final class ParametersValidator
{
    public function checkParameters(array $data, array $required, array $optional = []) : void
    {
        $errors = [];

        foreach ($required as $param) {
            if (!array_key_exists($param, $data)) {
                $errors[$param] = ParametersValidatorException::CODE_MISSING_PARAMETER;
            }
        }

        $allowed = array_merge($required, $optional);
        foreach (array_keys($data) as $key) {
            if (!in_array($key, $allowed, true)) {
                $errors[$param] = ParametersValidatorException::CODE_NOT_ALLOWED_PARAMETER;
            }
        }

        if (!empty($errors)) {
            throw new ParametersValidatorException($errors);
        }
    }
}
