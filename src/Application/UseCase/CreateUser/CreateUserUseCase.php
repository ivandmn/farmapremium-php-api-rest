<?php

declare(strict_types = 1);

namespace App\Application\UseCase\CreateUser;

use App\Domain\Factory\UserFactory;
use App\Domain\Repository\UserRepositoryInterface;

final class CreateUserUseCase
{
    public function __construct(
        private UserFactory             $userFactory,
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(CreateUserRequest $request) : CreateUserResponse
    {
        $user = $this->userFactory->register(
            $request->getName(),
            $request->getEmail()
        );

        $this->userRepository->save($user);

        return new CreateUserResponse($user);
    }
}
