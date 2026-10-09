<?php

declare(strict_types=1);

namespace App\Tests\Application\UseCase\GetTaskDetails;

use App\Application\UseCase\GetTaskDetails\GetTaskDetailsRequest;
use PHPUnit\Framework\TestCase;

final class GetTaskDetailsRequestTest extends TestCase
{
    public function test_getter_returns_task_id(): void
    {
        $req = new GetTaskDetailsRequest('018f9f9a-aaaa-bbbb-cccc-000000000001');
        $this->assertSame('018f9f9a-aaaa-bbbb-cccc-000000000001', $req->getTaskId());
    }

    public function test_allows_empty_string_currently(): void
    {
        $req = new GetTaskDetailsRequest('');
        $this->assertSame('', $req->getTaskId());
    }
}
