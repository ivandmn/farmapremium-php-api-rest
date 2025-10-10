<?php

declare(strict_types = 1);

namespace App\Infrastructure\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponse
{
    public const UNKNOWN_ERROR = 'Generic Error';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';

    public static function success(mixed $data = null, int $status = Response::HTTP_OK) : JsonResponse
    {
        $payload = ['status' => self::STATUS_SUCCESS];

        if ($data !== null) {
            $payload['data'] = $data;
        }

        return new JsonResponse($payload, $status);
    }

    public static function error(string $reason, int $status, ?string $extra = null) : JsonResponse
    {
        $payload = ['status' => self::STATUS_ERROR, 'reason' => $reason];

        if ($extra) {
            $payload['extra'] = $extra;
        }

        return new JsonResponse($payload, $status);
    }

    public static function empty() : JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public static function internalError() : JsonResponse
    {
        $payload = ['status' => self::STATUS_ERROR, 'reason' => self::UNKNOWN_ERROR];

        return new JsonResponse($payload, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
