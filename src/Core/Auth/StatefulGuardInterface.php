<?php

namespace App\Core\Auth;

interface StatefulGuardInterface extends GuardInterface
{
    public function login(Authenticatable $user): void;
    public function logout(): void;
    public function attempt(array $credentials): bool;
}
