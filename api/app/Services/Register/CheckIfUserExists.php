<?php

namespace App\Services\Register;

use App\Models\User;

class CheckIfUserExists
{
    public static function execute(User $user, string $email, string $document) : void
    {
        if ($user->document == $document) {
            throw new \Exception('DOCUMENT_EXISTS');
        }

        if ($user->email == $email) {
            throw new \Exception('EMAIL_EXISTS');
        }
    }
}
