<?php

declare(strict_types = 1);

namespace App\Infrastructure\Controller;

use App\Application\UseCase\AssignTaskToUser\AssignTaskToUserRequest;
use App\Application\UseCase\AssignTaskToUser\AssignTaskToUserUserCase;
use App\Application\UseCase\CreateTask\CreateTaskRequest;
use App\Application\UseCase\CreateTask\CreateTaskUserCase;
use App\Application\UseCase\DeleteTask\DeleteTaskRequest;
use App\Application\UseCase\DeleteTask\DeleteTaskUserCase;
use App\Application\UseCase\GetTaskDetails\GetTaskDetailsRequest;
use App\Application\UseCase\GetTaskDetails\GetTaskDetailsUserCase;
use App\Application\UseCase\ListTasks\ListTasksRequest;
use App\Application\UseCase\ListTasks\ListTasksUserCase;
use App\Application\UseCase\UpdateTask\UpdateTaskRequest;
use App\Application\UseCase\UpdateTask\UpdateTaskUserCase;
use App\Domain\Exception\Task\InvalidTaskDescription;
use App\Domain\Exception\Task\InvalidTaskStatusTransitionException;
use App\Domain\Exception\Task\TaskDeletionNotAllowedException;
use App\Domain\Exception\Task\UserNotFoundException;
use App\Domain\Exception\Task\InvalidTaskIdException;
use App\Domain\Exception\Task\InvalidTaskPriorityException;
use App\Domain\Exception\Task\InvalidTaskStatusException;
use App\Domain\Exception\Task\InvalidTaskTitleException;
use App\Domain\Exception\Task\TaskDueDateInPastException;
use App\Domain\Exception\Task\TaskNotFoundException;
use App\Domain\Exception\User\InvalidUserIdException;
use App\Application\Exception\InvalidDateFormat;
use App\Infrastructure\Exception\InvalidRequestException;
use App\Infrastructure\Exception\InvalidRequestParameterException;
use App\Infrastructure\Http\ApiResponse;
use App\Infrastructure\Http\Request\Task\AssignTaskToUserDto;
use App\Infrastructure\Http\Request\Task\CreateTaskRequestDto;
use App\Infrastructure\Http\Request\Task\ListTaskRequestDto;
use App\Infrastructure\Http\Request\Task\UpdateTaskRequestDto;
use App\Infrastructure\Service\ApiRequestValidator;
use App\Infrastructure\Service\MonologLogger;
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
        private readonly MonologLogger            $logger,
        private readonly ApiRequestValidator      $apiRequestValidator,
        private readonly CreateTaskUserCase       $createTaskUserCase,
        private readonly ListTasksUserCase        $listTasksUserCase,
        private readonly GetTaskDetailsUserCase   $getTaskDetailsUserCase,
        private readonly DeleteTaskUserCase       $deleteTaskUserCase,
        private readonly AssignTaskToUserUserCase $assignTaskToUserUserCase,
        private readonly UpdateTaskUserCase       $updateTaskUserCase,
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
                $data->page ? (int) $data->page : 1,
                $data->limit ? (int) $data->limit : 50,
            );

            $response = ($this->listTasksUserCase)($listRequest);

            return $response->isEmpty() ? ApiResponse::empty() : ApiResponse::success($response);
        } catch (InvalidRequestParameterException|InvalidRequestException|InvalidTaskStatusException|InvalidTaskPriorityException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Error Listing Tasks: %s', $exception->getMessage()),
                ['exception' => $exception]
            );

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
        } catch (InvalidTaskIdException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (TaskNotFoundException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Error Getting Task Details: %s', $exception->getMessage()),
                ['exception' => $exception]
            );

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
                $data->dueDate
            );

            $response = ($this->createTaskUserCase)($request);

            return ApiResponse::success($response);
        } catch (InvalidRequestParameterException|InvalidRequestException|InvalidTaskTitleException|InvalidTaskDescription|InvalidDateFormat|InvalidTaskPriorityException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (TaskDueDateInPastException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_CONFLICT);
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Error Creating Task: %s', $exception->getMessage()),
                ['exception' => $exception]
            );

            return ApiResponse::internalError();
        }
    }

    #[Route('/{id}', name: 'task_delete', methods: ['DELETE'])]
    public function delete(string $id) : JsonResponse
    {
        try {
            $request = new DeleteTaskRequest($id);

            $response = ($this->deleteTaskUserCase)($request);

            return ApiResponse::success($response);
        } catch (InvalidTaskIdException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (TaskNotFoundException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (TaskDeletionNotAllowedException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_CONFLICT);
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Error Deleting Task: %s', $exception->getMessage()),
                ['exception' => $exception]
            );

            return ApiResponse::internalError();
        }
    }

    #[Route('/{id}/assign', name: 'task_assign', methods: ['PATCH'])]
    public function assign(Request $request, string $id) : JsonResponse
    {
        try {
            /** @var AssignTaskToUserDto $data */
            $data = $this->apiRequestValidator->validate($request, AssignTaskToUserDto::class);

            $request = new AssignTaskToUserRequest(
                $id,
                $data->userId
            );

            $response = ($this->assignTaskToUserUserCase)($request);

            return ApiResponse::success($response);
        } catch (InvalidRequestParameterException|InvalidRequestException|InvalidUserIdException|InvalidTaskIdException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (UserNotFoundException|TaskNotFoundException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Error Assigning Task To User: %s', $exception->getMessage()),
                ['exception' => $exception]
            );

            return ApiResponse::internalError();
        }
    }

    #[Route('/{id}', name: 'task_update', methods: ['PUT'])]
    public function update(Request $request, string $id) : JsonResponse
    {
        try {
            /** @var UpdateTaskRequestDto $data */
            $data = $this->apiRequestValidator->validate($request, UpdateTaskRequestDto::class);

            $request = new UpdateTaskRequest(
                $id,
                $data->title,
                $data->description,
                $data->status,
                $data->priority,
                $data->dueDate
            );

            $response = ($this->updateTaskUserCase)($request);

            return ApiResponse::success($response);
        } catch (InvalidRequestParameterException|InvalidRequestException|InvalidTaskTitleException|InvalidTaskDescription|InvalidDateFormat|InvalidTaskStatusException|InvalidTaskPriorityException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (TaskNotFoundException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (TaskDueDateInPastException|InvalidTaskStatusTransitionException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_CONFLICT);
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Error Updating Task: %s', $exception->getMessage()),
                ['exception' => $exception]
            );

            return ApiResponse::internalError();
        }
    }
}
