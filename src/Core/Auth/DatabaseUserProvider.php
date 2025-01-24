<?php

namespace App\Core\Auth;

use App\Models\User;

class DatabaseUserProvider implements UserProviderInterface
{
    public function retrieveById(int $identifier): ?Authenticatable
    {
        return User::find($identifier);
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $email = $credentials['email'] ?? null;

        if (!$email) {
            return null;
        }

        //TODO: Add ->get() and first() in the base model. Delete findByEmail
        return User::findByEmail($email);
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        //TODO: Add password_verify
        $plain = $credentials['password'] ?? '';
        return $plain === $user->getAuthPassword();
    }
}