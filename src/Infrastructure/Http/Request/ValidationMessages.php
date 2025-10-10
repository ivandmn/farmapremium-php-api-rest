<?php

declare(strict_types = 1);

namespace App\Infrastructure\Http\Request;

final class ValidationMessages
{
    public const REQUIRED = 'Missing required parameter "{{ propertyPath }}"';
    public const TYPE = 'Parameter "{{ propertyPath }}" must be of type {{ type }}';
    public const LENGTH_MIN = 'Parameter "{{ propertyPath }}" must have at least {{ limit }} characters';
    public const LENGTH_MAX = 'Parameter "{{ propertyPath }}" cannot exceed {{ limit }} characters';
    public const CHOICE = 'Parameter "{{ propertyPath }}" must be one of the allowed values';
    public const DATE_ATOM = 'Parameter "{{ propertyPath }}" must be a valid ISO8601 (ATOM) datetime';
}
