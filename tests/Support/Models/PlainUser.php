<?php

namespace Pbac\Tests\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;

class PlainUser extends User
{
    use HasFactory;

    protected $fillable = ['email', 'password', 'name', 'is_super_admin'];

    public $timestamps = false;

    protected $table = 'users';
}
