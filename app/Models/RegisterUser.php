<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class RegisterUser extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
    ];

    public static function registerUser($email, $password)
    {
        return self::create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }
}