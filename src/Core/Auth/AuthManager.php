<?php

namespace App\Core\Auth;

use http\Exception\InvalidArgumentException;

class AuthManager
{
    protected array $guards = [];
    protected ?string $defaultGuard = null;
    protected array $guardResolvers = [];

    public function setDefaultGuard(string $name): void
    {
        $this->defaultGuard = $name;
    }

    public function resolveGuardUsing(string $name, callable $resolver): void
    {
        $this->guardResolvers[$name] = $resolver;
    }

    public function guard(?string $name = null)
    {
        $name = $name ?: $this->defaultGuard;

        if (!$name) {
            throw new InvalidArgumentException('No default guard is set');
        }

        if (isset($this->guards[$name])) {
            return $this->guards[$name];
        }

        if (!isset($this->guardResolvers[$name])) {
            throw new InvalidArgumentException("No guard resolver defined for [$name].");
        }

        $guard = $this->guardResolvers[$name]();

        if (!$guard instanceof GuardInterface) {
            throw new InvalidArgumentException("Guard for [$name] must implement GuardInterface.");
        }

        $this->guards[$name] = $guard;

        return $guard;
    }

    public function check(): bool
    {
        return $this->guard()->check();
    }

    public function user(): ?Authenticatable
    {
        return $this->guard()->user();
    }

    public function id(): ?int
    {
        return $this->guard()->id();
    }

    public function login(Authenticatable $user): void
    {
        $this->guard()->login($user);
    }

    public function logout(): void
    {
        $this->guard()->logout();
    }

    public function attempt(array $credentials): bool
    {
        return $this->guard()->attempt($credentials);
    }
}