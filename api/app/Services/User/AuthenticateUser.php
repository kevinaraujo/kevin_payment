<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticateUser
{
    public static function execute(string $email, string $password) : User
    {
        $user = User::query()
            ->where('email', $email)
            ->first();

        if (!$user) {
            throw new \Exception('INVALID_CREDENTIALS', 401);
        }

        if (!Hash::check($password, $user->password)) {
            throw new \Exception('INVALID_CREDENTIALS', 401);
        }

        return $user;
    }
}
