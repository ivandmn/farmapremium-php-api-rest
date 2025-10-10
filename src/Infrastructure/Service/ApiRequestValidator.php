<?php

declare(strict_types = 1);

namespace App\Infrastructure\Service;

use App\Infrastructure\Exception\InvalidRequestException;
use App\Infrastructure\Exception\InvalidRequestParameterException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

final class ApiRequestValidator
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface  $validator
    ) {
    }

    public function validate(Request $request, string $dtoClass) : object
    {
        try {
            $dto = $request->isMethod('GET')
                ? $this->serializer->denormalize($request->query->all(), $dtoClass)
                : $this->serializer->deserialize($request->getContent(), $dtoClass, 'json');

            return $this->validateDto($dto);
        } catch (NotEncodableValueException) {
            throw new InvalidRequestException('Invalid JSON');
        } catch (ExtraAttributesException) {
            throw new InvalidRequestException('Invalid payload');
        }
    }

    private function validateDto(object $dto) : object
    {
        $violations = $this->validator->validate($dto);

        if (empty($violations)) {
            return $dto;
        }

        $violation = $violations[0];
        $message = $violation->getMessage();

        throw new InvalidRequestParameterException($message);
    }
}
