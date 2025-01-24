<?php

namespace App\Core\Auth;

interface Authenticatable
{
    public function getAuthIdentifier(): int;

    public function getAuthPassword(): ?string;

}
