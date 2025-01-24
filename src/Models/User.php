<?php

namespace App\Models;

use App\Core\Auth\Authenticatable;
use App\Core\Model;

/**
 * @property int $id;
 * @property string $name;
 * @property string $email;
 * @property string $email_verified_at;
 * @property string $password;
 * @property string $remember_token;
 * @property string $created_at;
 * @property string $updated_at;
 */
class User extends Model implements Authenticatable
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

    public function getAuthIdentifier(): int
    {
        return $this->id;
    }

    public function getAuthPassword(): ?string
    {
        return $this->password ?? null;
    }
}
