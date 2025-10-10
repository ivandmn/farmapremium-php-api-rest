<?php

declare(strict_types = 1);

namespace App\Infrastructure\Controller;

use App\Application\UseCase\CreateUser\CreateUserRequest;
use App\Application\UseCase\CreateUser\CreateUserUseCase;
use App\Application\UseCase\ListUsers\ListUsersRequest;
use App\Application\UseCase\ListUsers\ListUsersUseCase;
use App\Domain\Exception\User\InvalidUserEmailException;
use App\Domain\Exception\User\InvalidUserIdException;
use App\Domain\Exception\User\InvalidUserNameException;
use App\Domain\Exception\User\UserAlreadyExistsException;
use App\Infrastructure\Exception\InvalidParameterException;
use App\Infrastructure\Exception\InvalidRequestArgumentException;
use App\Infrastructure\Exception\InvalidRequestParameterException;
use App\Infrastructure\Exception\MissingRequestParameterException;
use App\Infrastructure\Http\ApiResponse;
use App\Infrastructure\Service\ApiRequestValidator;
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
        private ApiRequestValidator $apiRequestValidator,
        private CreateUserUseCase   $createUserUseCase,
        private ListUsersUseCase    $listUsersUseCase
    ) {
    }

    #[Route('', name: 'user_list', methods: ['GET'])]
    public function list() : JsonResponse
    {
        try {
            $request = new ListUsersRequest();
            $response = ($this->listUsersUseCase)($request);

            return empty($response) ? ApiResponse::empty() : ApiResponse::success($response);
        } catch (Throwable) {
            return ApiResponse::internalError();
        }
    }

    #[Route('', name: 'user_create', methods: ['POST'])]
    public function create(Request $request) : JsonResponse
    {
        try {
            $this->apiRequestValidator->validate($request, [
                'email' => ['type' => 'string', 'required' => true],
                'name' => ['type' => 'string', 'required' => true],
            ]);

            $data = $this->apiRequestValidator->getData();

            $request = new CreateUserRequest(
                $data['email'],
                $data['name']
            );

            $response = ($this->createUserUseCase)($request);

            return ApiResponse::success($response);
        } catch (MissingRequestParameterException|InvalidRequestParameterException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (InvalidRequestArgumentException|InvalidUserEmailException|InvalidUserIdException|InvalidUserNameException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (UserAlreadyExistsException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_FORBIDDEN);
        } catch (Throwable) {
            return ApiResponse::internalError();
        }
    }
}
