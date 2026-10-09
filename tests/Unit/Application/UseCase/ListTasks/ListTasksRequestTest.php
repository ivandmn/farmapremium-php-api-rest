<?php

declare(strict_types=1);

namespace App\Tests\Application\UseCase\ListTasks;

use App\Application\UseCase\ListTasks\ListTasksRequest;
use PHPUnit\Framework\TestCase;

final class ListTasksRequestTest extends TestCase
{
    public function test_getters_return_values(): void
    {
        $req = new ListTasksRequest('pending', 'low', 2, 10);

        $this->assertSame('pending', $req->getStatus());
        $this->assertSame('low', $req->getPriority());
        $this->assertSame(2, $req->getPage());
        $this->assertSame(10, $req->getLimit());
    }

    public function test_defaults_for_page_and_limit_when_omitted(): void
    {
        $req = new ListTasksRequest(null, null);

        $this->assertNull($req->getStatus());
        $this->assertNull($req->getPriority());
        $this->assertSame(1, $req->getPage());
        $this->assertSame(50, $req->getLimit());
    }

    public function test_accepts_nulls_for_filters_and_pagination(): void
    {
        $req = new ListTasksRequest(null, null, null, null);

        $this->assertNull($req->getStatus());
        $this->assertNull($req->getPriority());
        $this->assertNull($req->getPage());
        $this->assertNull($req->getLimit());
    }
}
