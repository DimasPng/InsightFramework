<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected static string $table = 'users';

    protected array $fillable = [
        'name',
        'email',
        'password'
    ];

    public function setPassword(string $plainPassword): void
    {
        $this->attributes['password'] = password_hash($plainPassword, PASSWORD_BCRYPT);
    }
}
