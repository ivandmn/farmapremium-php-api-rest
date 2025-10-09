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
use App\Infrastructure\Exception\InvalidRequestArgumentException;
use App\Infrastructure\Exception\ParametersValidatorException;
use App\Infrastructure\Service\ParametersValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use TypeError;

#[Route('/api/users')]
class UserController extends AbstractController
{
    public function __construct(
        private ParametersValidator $validator,
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

            if (empty($response)) {
                return new JsonResponse(null, Response::HTTP_NO_CONTENT);
            }

            return new JsonResponse(['status' => 'success', 'data' => $response], Response::HTTP_CREATED);
        } catch (InvalidUserEmailException|InvalidUserIdException|InvalidUserNameException $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (UserAlreadyExistsException $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (Throwable $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => 'Generic Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('', name: 'user_create', methods: ['POST'])]
    public function create(Request $request) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse(['status' => 'error', 'reason' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->validator->checkParameters($data, ['email', 'name']);

            $request = new CreateUserRequest($data['email'] ?? '', $data['name'] ?? '');
            $response = ($this->createUserUseCase)($request);

            return new JsonResponse(['status' => 'success', 'data' => $response], Response::HTTP_CREATED);
        } catch (ParametersValidatorException $exception) {
            $error = $exception->getFirstError();

            return new JsonResponse(['status' => 'error', 'reason' => 'Invalid request payload', 'extra' => $exception->getErrorMessage($error)], Response::HTTP_BAD_REQUEST);
        } catch (TypeError $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => 'Invalid request payload'], Response::HTTP_BAD_REQUEST);
        } catch (InvalidRequestArgumentException|InvalidUserEmailException|InvalidUserIdException|InvalidUserNameException $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (UserAlreadyExistsException $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => $exception->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (Throwable $exception) {
            dump($exception);
            return new JsonResponse(['status' => 'error', 'reason' => 'Generic Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
