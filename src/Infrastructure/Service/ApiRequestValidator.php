<?php

declare(strict_types = 1);

namespace App\Infrastructure\Service;

use App\Infrastructure\Exception\InvalidRequestParameterException;
use App\Infrastructure\Exception\MissingRequestParameterException;
use Symfony\Component\HttpFoundation\Request;

final class ApiRequestValidator
{
    private array $data = [];

    public function validate(Request $request, array $schema) : void
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        if (!is_array($data)) {
            throw new InvalidRequestParameterException('Invalid or empty JSON payload.');
        }

        foreach ($schema as $param => $rules) {
            $isRequired = $rules['required'] ?? false;
            $expectedType = $rules['type'] ?? null;

            if ($isRequired && !array_key_exists($param, $data)) {
                throw new MissingRequestParameterException(sprintf(
                    'Missing required parameter "%s".',
                    $param
                ));
            }

            if (!array_key_exists($param, $data)) {
                continue;
            }

            $actualType = get_debug_type($data[$param]);

            if ($expectedType && $actualType !== $expectedType) {
                throw new InvalidRequestParameterException(sprintf(
                    'Parameter "%s" must be of type "%s", "%s" given.',
                    $param,
                    $expectedType,
                    $actualType
                ));
            }

            $this->data[$param] = is_string($data[$param])
                ? trim($data[$param])
                : $data[$param];
        }
    }

    public function getData() : array
    {
        return $this->data;
    }

    public function get(string $key, mixed $default = null) : mixed
    {
        return $this->data[$key] ?? $default;
    }
}
