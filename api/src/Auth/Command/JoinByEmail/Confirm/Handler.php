<?php

namespace App\Auth\Command\JoinByEmail\Confirm;

use App\Auth\Entity\User\UserRepository;
use App\Flusher;

class Handler
{
    public function __construct(UserRepository $users, Flusher $flusher){
        $this->users = $users;
        $this->flusher = $flusher;
    }
    public function handle(Command $command): void
    {
        if(!$user = $this->users->findByJoinConfirmToken($command->token)){
            throw new \DomainException('Invalid confirm token');
        }
        $user->confirmJoin($command->token, new \DateTimeImmutable());

        $this->flusher->flush();
    }
}