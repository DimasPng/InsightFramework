<?php

namespace App\Core\Auth;

interface GuardInterface
{
    public function check(): bool;
    public function user(): ?Authenticatable;
    public function id(): ?int;
}
