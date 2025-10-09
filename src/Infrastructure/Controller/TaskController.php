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
use App\Infrastructure\Exception\ParametersValidatorException;
use App\Infrastructure\Service\ParametersValidator;
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
        private ParametersValidator $validator,
        private CreateTaskUserCase  $createTaskUserCase,
        private ListTasksUserCase   $listTasksUserCase
    ) {
    }

    #[Route('', name: 'task_list', methods: ['GET'])]
    public function list(Request $request) : JsonResponse
    {
        try {
            $status = $request->request->get('status') ?: null;
            $priority = $request->request->get('priority') ?: null;

            $listRequest = new ListTasksRequest(
                $status ? (string) $status : null,
                $priority ? (string) $priority : null
            );

            $response = ($this->listTasksUserCase)($listRequest);

            if (empty($response)) {
                return new JsonResponse(null, Response::HTTP_NO_CONTENT);
            }

            return new JsonResponse(['status' => 'success', 'data' => $response], Response::HTTP_OK);
        } catch (InvalidTaskStatusException|InvalidTaskPriorityException $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => 'Generic Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('', name: 'task_create', methods: ['POST'])]
    public function create(Request $request) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse(['status' => 'error', 'reason' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->validator->checkParameters($data, ['title', 'description', 'priority'], ['userId', 'dueDate']);

            $request = new CreateTaskRequest($data['title'] ?? '', $data['description'] ?? '', $data['priority'] ?? '', $data['userId'] ?? null, $data['dueDate'] ?? null);
            $response = ($this->createTaskUserCase)($request);

            return new JsonResponse(['status' => 'success', 'data' => $response], Response::HTTP_CREATED);
        } catch (ParametersValidatorException $exception) {
            $error = $exception->getFirstError();

            return new JsonResponse(['status' => 'error', 'reason' => 'Invalid request payload', 'extra' => $exception->getErrorMessage($error)], Response::HTTP_BAD_REQUEST);
        } catch (TypeError $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => 'Invalid request payload'], Response::HTTP_BAD_REQUEST);
        } catch (InvalidRequestArgumentException|TaskDueDateInPastException|InvalidTaskPriorityException|InvalidTaskTitleException $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return new JsonResponse(['status' => 'error', 'reason' => 'Generic Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
