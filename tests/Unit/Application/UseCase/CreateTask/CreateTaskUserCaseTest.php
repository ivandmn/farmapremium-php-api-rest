<?php

declare(strict_types = 1);

namespace App\Tests\Application\UseCase\CreateTask;

use App\Application\Service\LoggerInterface;
use App\Application\UseCase\CreateTask\CreateTaskRequest;
use App\Application\UseCase\CreateTask\CreateTaskUserCase;
use App\Domain\Factory\TaskFactory;
use App\Domain\Model\Task;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\ValueObject\Task\TaskDescription;
use App\Domain\ValueObject\Task\TaskDueDate;
use App\Domain\ValueObject\Task\TaskPriority;
use App\Domain\ValueObject\Task\TaskTitle;
use DateTime;
use PHPUnit\Framework\TestCase;

final class CreateTaskUserCaseTest extends TestCase
{
    public function test_happy_path_creates_task_persists_and_logs() : void
    {
        $repo = $this->createMock(TaskRepositoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $request = new CreateTaskRequest(
            'Test Title',
            'Test Description',
            'medium',
            '2030-01-01T00:00:00+00:00'
        );

        $repo->expects($this->once())
            ->method('create')
            ->with($this->callback(function (Task $task) {
                return $task->getTitle()->equals(TaskTitle::fromString('Test Title'))
                    && $task->getDescription()->equals(TaskDescription::fromString('Test Description'))
                    && $task->getPriority()->equals(TaskPriority::fromString('medium'))
                    && $task->getDueDate()?->equals(TaskDueDate::fromDate(new DateTime('2030-01-01T00:00:00+00:00'))) === true;
            }));

        $logger->expects($this->once())
            ->method('info')
            ->with(
                'Task Created',
                $this->callback(function (array $ctx) {
                    return isset($ctx['task_id'], $ctx['task_title']) && $ctx['task_title'] === 'Test Title';
                })
            );

        $uc = new CreateTaskUserCase(new TaskFactory(), $repo, $logger);
        $response = $uc($request);

        $this->assertNotNull($response);
    }

    public function test_propagates_domain_exception_on_invalid_title() : void
    {
        $repo = $this->createMock(TaskRepositoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $uc = new CreateTaskUserCase(new TaskFactory(), $repo, $logger);

        $this->expectException(\App\Domain\Exception\Task\InvalidTaskTitleException::class);

        $uc(new CreateTaskRequest(
            'bad',
            'Ok description',
            'low',
            '2030-01-01T00:00:00+00:00'
        ));
    }
}
