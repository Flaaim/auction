<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

interface UserRepository
{
    public function hasByEmail(Email $email): bool;
    public function add(User $user): void;
    public function findByConfirmToken(string $token): ?User;
    public function hasByNetwork(NetworkIdentity $identity): bool;
    public function findByPasswordResetToken(string $token): ?User;
    /**
     * @param Id $id
     * @return User
     * @throws \DomainException
     */
    public function get(Id $id): User;

    /**
     * @param Email $email
     * @return User
     * @throws \DomainException
     */
    public function getByEmail(Email $email): User;
    public function findByNewEmailToken(string $token): ?User;
    public function remove(User $user): void;
}