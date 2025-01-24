<?php

namespace App\Core\Auth;

interface UserProviderInterface
{
    public function retrieveById(int $identifier): ?Authenticatable;
    public function retrieveByCredentials(array $credentials): ?Authenticatable;
    public function validateCredentials(Authenticatable $user, array $credentials): bool;
}