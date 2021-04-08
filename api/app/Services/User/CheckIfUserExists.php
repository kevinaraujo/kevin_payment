<?php

namespace App\Services\User;

use App\Models\User;

class CheckIfUserExists
{
    public static function execute(string $email, string $document) : void
    {
        $user = User::query()
            ->where('email', $email)
            ->orWhere('document', $document)
            ->first();

        if (!$user) {
            return;
        }

        ThrowRightUserMessageException::execute(
            $user,
            $email,
            $document
        );
    }
}
