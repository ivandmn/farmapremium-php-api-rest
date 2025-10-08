<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserName;
use DateTimeImmutable;

final class User
{
	public function __construct(
		private UserId $id,
		private UserEmail $email,
		private UserName $name,
		private readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
		private DateTimeImmutable $updatedAt = new DateTimeImmutable(),
	) {
	}

	public function getId(): UserId
	{
		return $this->id;
	}

	public function getEmail(): UserEmail
	{
		return $this->email;
	}

	public function getName(): UserName
	{
		return $this->name;
	}

	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function getUpdatedAt(): DateTimeImmutable
	{
		return $this->updatedAt;
	}

	// Métodos de comportamiento (no setters)
	public function changeEmail(UserEmail $newEmail): void
	{
		if ($this->email->equals($newEmail)) {
			return;
		}

		$this->email = $newEmail;
		$this->markAsUpdated();
	}

	public function changeName(UserName $newName): void
	{
		if ($this->name->equals($newName)) {
			return;
		}

		$this->name = $newName;
		$this->markAsUpdated();
	}

	// Métodos de consulta
	public function hasEmail(UserEmail $email): bool
	{
		return $this->email->equals($email);
	}

	public function isActive(): bool
	{
		// Lógica de negocio para determinar si el usuario está activo
		return true; // Implementar según reglas de negocio
	}

	private function markAsUpdated(): void
	{
		$this->updatedAt = new DateTimeImmutable();
	}
}