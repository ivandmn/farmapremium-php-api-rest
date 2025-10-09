<?php

declare(strict_types = 1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\User as UserDomain;
use App\Domain\ValueObject\User\UserEmail;
use App\Infrastructure\Persistence\Doctrine\Entity\User as UserDoctrine;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\User\UserId;
use App\Infrastructure\Persistence\Doctrine\Mapper\UserMapper;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(UserDomain $user) : void
    {
        $this->entityManager->persist(UserMapper::toDoctrine($user));
        $this->entityManager->flush();
    }

    public function delete(UserDomain $user) : void
    {
        $this->entityManager->remove(UserMapper::toDoctrine($user));
        $this->entityManager->flush();
    }

    public function findAll() : array
    {
        $entities = $this->entityManager->getRepository(UserDoctrine::class)->findAll();

        return array_map([UserMapper::class, 'toDomain'], $entities);
    }

    public function findById(UserId $id) : ?UserDomain
    {
        $user = $this->entityManager->getRepository(UserDoctrine::class)->find($id->value());

        return $user ? UserMapper::toDomain($user) : null;
    }

    public function findByEmail(UserEmail $email) : ?UserDomain
    {
        $entity = $this->entityManager->getRepository(UserDoctrine::class)->findOneBy(['email' => $email->value()]);

        return $entity ? UserMapper::toDomain($entity) : null;
    }
}
