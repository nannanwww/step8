<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class RegisterUser extends Model
{

    public static function registerUser($email, $password)
    {
        return self::create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }
}