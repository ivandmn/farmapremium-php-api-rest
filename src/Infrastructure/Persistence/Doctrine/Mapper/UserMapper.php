<?php

namespace App\Infrastructure\Persistence\Doctrine\Mapper;

use App\Domain\Model\User as DomainUser;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use App\Infrastructure\Persistence\Doctrine\Entity\User as DoctrineUser;

class UserMapper
{
    public static function toDomain(DoctrineUser $entity) : DomainUser
    {
        return new DomainUser(
            UserId::fromString($entity->getId()),
            UserEmail::fromString($entity->getEmail()),
            UserName::fromString($entity->getName()),
            $entity->getCreatedAt()
        );
    }

    public static function toDoctrine(DomainUser $user) : DoctrineUser
    {
        $entity = new DoctrineUser();

        $entity->setId($user->getId()->value());
        $entity->setEmail($user->getEmail()->value());
        $entity->setName($user->getName()->value());
        $entity->setCreatedAt($user->getCreatedAt());

        return $entity;
    }
}
