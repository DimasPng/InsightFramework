<?php

namespace App\Core\Auth;

interface GuardInterface
{
    public function check(): bool;
    public function user(): ?Authenticatable;
    public function id(): ?int;
    public function login(Authenticatable $user): void;
    public function logout(): void;
    public function attempt(array $credentials): bool;
}
