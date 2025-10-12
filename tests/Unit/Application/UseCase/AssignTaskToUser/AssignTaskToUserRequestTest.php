<?php

declare(strict_types = 1);

namespace App\Tests\Application\UseCase\AssignTaskToUser;

use App\Application\UseCase\AssignTaskToUser\AssignTaskToUserRequest;
use PHPUnit\Framework\TestCase;

final class AssignTaskToUserRequestTest extends TestCase
{
    public function test_getters_return_given_values() : void
    {
        $req = new AssignTaskToUserRequest(
            '018f9f9a-aaaa-bbbb-cccc-000000000001',
            '018f9f9a-dddd-eeee-ffff-000000000002'
        );

        $this->assertSame('018f9f9a-aaaa-bbbb-cccc-000000000001', $req->getTaskId());
        $this->assertSame('018f9f9a-dddd-eeee-ffff-000000000002', $req->getUserId());
    }

    public function test_allows_empty_strings_currently() : void
    {
        $req = new AssignTaskToUserRequest('', '');

        $this->assertSame('', $req->getTaskId());
        $this->assertSame('', $req->getUserId());
    }

    public function test_does_not_trim_or_normalize_input() : void
    {
        $req = new AssignTaskToUserRequest('  task  ', "\tuser\n");

        $this->assertSame('  task  ', $req->getTaskId());
        $this->assertSame("\tuser\n", $req->getUserId());
    }
}
