<?php

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: UserId::LENGTH, unique: true, options: ['fixed' => true])]
    private string $id;

    #[ORM\Column(type: 'string', length: UserEmail::MAX_LENGTH, unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(type: 'string', length: UserName::MAX_LENGTH, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at')]
    private DateTimeImmutable $createdAt;

    public function getId() : string
    {
        return $this->id;
    }

    public function setId(string $id) : User
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function setEmail(string $email) : User
    {
        $this->email = $email;

        return $this;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : User
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt) : User
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
