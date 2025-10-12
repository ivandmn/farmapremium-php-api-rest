<?php

declare(strict_types = 1);

namespace App\Infrastructure\Controller;

use App\Application\UseCase\CreateUser\CreateUserRequest;
use App\Application\UseCase\CreateUser\CreateUserUseCase;
use App\Application\UseCase\ListUsers\ListUsersRequest;
use App\Application\UseCase\ListUsers\ListUsersUseCase;
use App\Domain\Exception\User\InvalidUserEmailException;
use App\Domain\Exception\User\InvalidUserNameException;
use App\Domain\Exception\User\UserAlreadyExistsException;
use App\Infrastructure\Exception\InvalidRequestParameterException;
use App\Infrastructure\Http\Request\User\CreateUserRequestDto;
use App\Infrastructure\Exception\InvalidRequestException;
use App\Infrastructure\Http\ApiResponse;
use App\Infrastructure\Service\ApiRequestValidator;
use App\Infrastructure\Service\MonologLogger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/api/users')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly MonologLogger       $logger,
        private readonly ApiRequestValidator $apiRequestValidator,
        private readonly CreateUserUseCase   $createUserUseCase,
        private readonly ListUsersUseCase    $listUsersUseCase
    ) {
    }

    #[Route('', name: 'api_user_list', methods: ['GET'])]
    public function list() : JsonResponse
    {
        try {
            $request = new ListUsersRequest();
            $response = ($this->listUsersUseCase)($request);

            return $response->isEmpty() ? ApiResponse::empty() : ApiResponse::success($response);
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Error Listing Users: %s', $exception->getMessage()),
                ['exception' => $exception]
            );

            return ApiResponse::internalError();
        }
    }

    #[Route('', name: 'api_user_create', methods: ['POST'])]
    public function create(Request $request) : JsonResponse
    {
        try {
            /** @var CreateUserRequestDto $data */
            $data = $this->apiRequestValidator->validate($request, CreateUserRequestDto::class);

            $request = new CreateUserRequest(
                $data->email,
                $data->name
            );

            $response = ($this->createUserUseCase)($request);

            return ApiResponse::success($response);
        } catch (InvalidRequestParameterException|InvalidRequestException|InvalidUserEmailException|InvalidUserNameException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (UserAlreadyExistsException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_CONFLICT);
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Error Creating User: %s', $exception->getMessage()),
                ['exception' => $exception]
            );

            return ApiResponse::internalError();
        }
    }
}
