<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    protected $fillable = ['name', 'email', 'password', 'is_active'];
    protected $hidden = ['password'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return ['type' => 'admin'];
    }
}
