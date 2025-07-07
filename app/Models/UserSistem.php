<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSistem extends Model
{
    use HasFactory;

    protected $table = 'user_sistems';
    protected $fillable = ['nama', 'username', 'password', 'role'];
}
