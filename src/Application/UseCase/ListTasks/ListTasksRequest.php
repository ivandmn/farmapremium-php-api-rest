<?php

declare(strict_types = 1);

namespace App\Application\UseCase\ListTasks;

final class ListTasksRequest
{
    //TODO: Add more filters
    public function __construct(
        private ?string $status,
        private ?string $priority,
        private ?int    $page = 1,
        private ?int    $limit = 10
    ) {
    }

    public function getStatus() : string
    {
        return $this->status;
    }

    public function getPriority() : string
    {
        return $this->priority;
    }

    public function getPage() : ?int
    {
        return $this->page;
    }

    public function getLimit() : ?int
    {
        return $this->limit;
    }
}
