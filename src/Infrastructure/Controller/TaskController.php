<?php

declare(strict_types = 1);

namespace App\Infrastructure\Controller;

use App\Application\UseCase\CreateTask\CreateTaskRequest;
use App\Application\UseCase\CreateTask\CreateTaskUserCase;
use App\Application\UseCase\ListTasks\ListTasksRequest;
use App\Application\UseCase\ListTasks\ListTasksUserCase;
use App\Domain\Exception\Task\InvalidTaskPriorityException;
use App\Domain\Exception\Task\InvalidTaskStatusException;
use App\Domain\Exception\Task\InvalidTaskTitleException;
use App\Domain\Exception\Task\TaskDueDateInPastException;
use App\Infrastructure\Exception\InvalidRequestArgumentException;
use App\Infrastructure\Exception\InvalidRequestException;
use App\Infrastructure\Exception\InvalidRequestParameterException;
use App\Infrastructure\Http\ApiResponse;
use App\Infrastructure\Http\Request\Task\ListTaskRequestDto;
use App\Infrastructure\Service\ApiRequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use TypeError;

#[Route('/api/tasks')]
class TaskController extends AbstractController
{
    public function __construct(
        private ApiRequestValidator $apiRequestValidator,
        private CreateTaskUserCase  $createTaskUserCase,
        private ListTasksUserCase   $listTasksUserCase
    ) {
    }

    #[Route('', name: 'task_list', methods: ['GET'])]
    public function list(Request $request) : JsonResponse
    {
        try {
            /** @var ListTaskRequestDto $data */
            $data = $this->apiRequestValidator->validate($request, ListTaskRequestDto::class)

            $listRequest = new ListTasksRequest(
                $data->status,
                $data->priority
            );

            $response = ($this->listTasksUserCase)($listRequest);

            return $response->isEmpty() ? ApiResponse::empty() : ApiResponse::success($response);
        } catch (InvalidRequestParameterException|InvalidRequestException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (InvalidTaskStatusException|InvalidTaskPriorityException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable) {
            return ApiResponse::internalError();
        }
    }

    #[Route('', name: 'task_create', methods: ['POST'])]
    public function create(Request $request) : JsonResponse
    {
        try {
            $this->apiRequestValidator->validate($request, [
                'title' => ['type' => 'string', 'required' => true],
                'description' => ['type' => 'string', 'required' => true],
                'priority' => ['type' => 'string', 'required' => true],
                'userId' => ['type' => 'string', 'required' => false],
                'dueDate' => ['type' => 'string', 'required' => false],
            ]);

            $data = $this->apiRequestValidator->getData();

            $request = new CreateTaskRequest(
                $data['title'],
                $data['description'],
                $data['priority'],
                $data['userId'],
                $data['dueDate']
            );

            $response = ($this->createTaskUserCase)($request);

            return ApiResponse::success($response);
        } catch (MissingRequestParameterException|InvalidRequestParameterException|InvalidRequestArgumentException|TaskDueDateInPastException|InvalidTaskPriorityException|InvalidTaskTitleException $exception) {
            return ApiResponse::error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable) {
            return ApiResponse::internalError();
        }
    }
}
