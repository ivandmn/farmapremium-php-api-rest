<?php

declare(strict_types = 1);

namespace App\Application\UseCase\CreateUser;

use App\Domain\Factory\UserFactory;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\Exception\User\UserAlreadyExistsException;
use App\Domain\ValueObject\User\UserName;

final readonly class CreateUserUseCase
{
    public function __construct(
        private UserFactory             $userFactory,
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(CreateUserRequest $request) : CreateUserResponse
    {
        $emailUser = UserEmail::fromString($request->getEmail());
        $nameUser = UserName::fromString($request->getName());

        if ($this->userRepository->findByEmail($emailUser)) {
            throw new UserAlreadyExistsException('User with this email already exists');
        }

        $user = $this->userFactory->register(
            $emailUser,
            $nameUser
        );

        $this->userRepository->save($user);

        return new CreateUserResponse($user);
    }
}
