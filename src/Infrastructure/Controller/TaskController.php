<?php

declare(strict_types = 1);

namespace App\Infrastructure\Controller;

use App\Application\UseCase\CreateTask\CreateTaskRequest;
use App\Application\UseCase\CreateTask\CreateTaskUserCase;
use App\Application\UseCase\GetTaskDetails\GetTaskDetailsRequest;
use App\Application\UseCase\GetTaskDetails\GetTaskDetailsUserCase;
use App\Application\UseCase\ListTasks\ListTasksRequest;
use App\Application\UseCase\ListTasks\ListTasksUserCase;
use App\Domain\Exception\Task\AssignedUserDoesNotExistException;
use App\Domain\Exception\Task\InvalidTaskIdException;
use App\Domain\Exception\Task\InvalidTaskPriorityException;
use App\Domain\Exception\Task\InvalidTaskStatusException;
use App\Domain\Exception\Task\InvalidTaskTitleException;
use App\Domain\Exception\Task\TaskDueDateInPastException;
use App\Domain\Exception\Task\TaskNotFoundException;
use App\Domain\Exception\User\InvalidUserIdException;
use App\Infrastructure\Exception\InvalidRequestArgumentException;
use App\Infrastructure\Exception\InvalidRequestException;
use App\Infrastructure\Exception\InvalidRequestParameterException;
use App\Infrastructure\Http\ApiResponse;
use App\Infrastructure\Http\Request\Task\CreateTaskRequestDto;
use App\Infrastructure\Http\Request\Task\ListTaskRequestDto;
use App\Infrastructure\Service\ApiRequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/api/tasks')]
class TaskController extends AbstractController
{
    public function __construct(
        private ApiRequestValidator             $apiRequestValidator,
        private CreateTaskUserCase              $createTaskUserCase,
        private ListTasksUserCase               $listTasksUserCase,
        private readonly GetTaskDetailsUserCase $getTaskDetailsUserCase
    ) {
    }

    #[Route('', name: 'task_list', methods: ['GET'])]
    public function list(Request $request) : JsonResponse
    {
        try {
            /** @var ListTaskRequestDto $data */
            $data = $this->apiRequestValidator->validate($request, ListTaskRequestDto::class);

            $listRequest = new ListTasksRequest(
                $data->status,
                $data->priority,
                $data->page ? (int) $data->page : null,
                $data->limit ? (int) $data->limit : null,
            );

            $response = ($this->listTasksUserCase)($listRequest);

            return $response->isEmpty() ? ApiResponse::empty() : ApiResponse::success($response);
        } catch (InvalidRequestParameterException|InvalidRequestException|InvalidTaskStatusException|InvalidTaskPriorityException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return ApiResponse::internalError();
        }
    }

    #[Route('', name: 'task_create', methods: ['POST'])]
    public function create(Request $request) : JsonResponse
    {
        try {
            /** @var CreateTaskRequestDto $data */
            $data = $this->apiRequestValidator->validate($request, CreateTaskRequestDto::class);

            $request = new CreateTaskRequest(
                $data->title,
                $data->description,
                $data->priority,
                $data->userId,
                $data->dueDate
            );

            $response = ($this->createTaskUserCase)($request);

            return ApiResponse::success($response);
        } catch (InvalidRequestParameterException|InvalidRequestException|InvalidRequestArgumentException|TaskDueDateInPastException|InvalidTaskPriorityException|InvalidTaskTitleException|InvalidUserIdException|AssignedUserDoesNotExistException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return ApiResponse::internalError();
        }
    }

    #[Route('/{id}', name: 'task_detail', methods: ['GET'])]
    public function getById(string $id) : JsonResponse
    {
        try {
            $request = new GetTaskDetailsRequest($id);

            $response = ($this->getTaskDetailsUserCase)($request);

            return ApiResponse::success($response);
        } catch (TaskNotFoundException|InvalidTaskIdException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable) {
            return ApiResponse::internalError();
        }
    }

    #[Route('/{id}', name: 'task_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(string $id) : JsonResponse
    {
        try {
            // $this->deleteTaskUserCase->execute($id);
            return ApiResponse::success(['message' => sprintf('Task %s deleted (mocked)', $id)]);
        } catch (Throwable) {
            return ApiResponse::internalError();
        }
    }

    #[Route('/{id}/assign', name: 'task_assign', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function assign(Request $request, string $id) : JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $userId = $data['userId'] ?? null;

            if (!$userId) {
                return ApiResponse::error('Missing userId parameter', Response::HTTP_BAD_REQUEST);
            }

            // $this->assignTaskUserCase->execute($id, $userId);
            return ApiResponse::success([
                'message' => sprintf('Task %s assigned to user %s (mocked)', $id, $userId),
            ]);
        } catch (InvalidUserIdException|AssignedUserDoesNotExistException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable) {
            return ApiResponse::internalError();
        }
    }

    #[Route('/{id}', name: 'task_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(Request $request, string $id) : JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // $response = ($this->updateTaskUserCase)($id, $data);
            return ApiResponse::success([
                'id' => $id,
                'message' => 'Task updated (mocked)',
                'data' => $data,
            ]);
        } catch (InvalidRequestException|InvalidRequestParameterException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable) {
            return ApiResponse::internalError();
        }
    }
}
