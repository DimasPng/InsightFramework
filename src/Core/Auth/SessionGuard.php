<?php

namespace App\Core\Auth;

class SessionGuard implements StatefulGuardInterface
{
    protected ?Authenticatable $user = null;
    protected string $sessionKey = 'auth_user_id';

    public function __construct(
        protected UserProviderInterface $provider
    )
    {
        $this->initUserFromSession();
    }

    protected function initUserFromSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION[$this->sessionKey])) {
            $userId = $_SESSION[$this->sessionKey];

            $this->user = $this->provider->retrieveById($userId);
        }
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function user(): ?Authenticatable
    {
        return $this->user;
    }

    public function id(): ?int
    {
        return $this->user?->getAuthIdentifier();
    }

    public function login(Authenticatable $user): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->user = $user;
        $_SESSION[$this->sessionKey] = $user->getAuthIdentifier();
    }

    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->user = null;
        unset($_SESSION[$this->sessionKey]);
    }

    public function attempt(array $credentials): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user && $this->provider->validateCredentials($user, $credentials)) {
            $this->login($user);
            return true;
        }

        return false;
    }
}
