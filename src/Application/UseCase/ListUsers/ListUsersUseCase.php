<?php

declare(strict_types = 1);

namespace App\Application\UseCase\ListUsers;

use App\Domain\Repository\UserRepositoryInterface;

final class ListUsersUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(ListUsersRequest $request) : ListsUserResponse
    {
        $users = $this->userRepository->findAll();

        return new ListsUserResponse($users);
    }
}
