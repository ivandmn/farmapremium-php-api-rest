<?php

declare(strict_types=1);

namespace App\Tests\Application\UseCase\ListUsers;

use App\Application\UseCase\ListUsers\ListUsersRequest;
use PHPUnit\Framework\TestCase;

final class ListUsersRequestTest extends TestCase
{
    public function test_can_instantiate_request(): void
    {
        $req = new ListUsersRequest();
        $this->assertInstanceOf(ListUsersRequest::class, $req);
    }
}
