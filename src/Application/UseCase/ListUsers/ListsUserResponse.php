<?php

declare(strict_types = 1);

namespace App\Application\UseCase\ListUsers;

use App\Domain\Model\User;
use ArrayIterator;

final readonly class ListsUserResponse implements \JsonSerializable, \Countable, \IteratorAggregate
{
    public function __construct(
        private array $users
    ) {
    }

    public function jsonSerialize() : array
    {
        return array_map(
            static fn(User $user) : array => [
                'id' => $user->getId()->value(),
                'email' => $user->getEmail()->value(),
                'name' => $user->getName()->value(),
                'createdAt' => $user->getCreatedAt()->format(DATE_ATOM),
            ],
            $this->users
        );
    }

    public function count() : int
    {
        return count($this->users);
    }

    public function getIterator() : \Traversable
    {
        return new ArrayIterator($this->users);
    }

    public function isEmpty() : bool
    {
        return $this->users === [];
    }
}
