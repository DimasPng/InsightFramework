<?php

namespace App\Core\Auth;

interface HasApiTokenInterface
{
    public function getApiToken(): ?string;
    public function setApiToken(string $token): void;
}
