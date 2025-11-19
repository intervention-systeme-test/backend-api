<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'account_type',
        'address',
        'company_name',
        'cfe_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
