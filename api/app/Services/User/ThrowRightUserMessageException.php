<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Http\Response;

class ThrowRightUserMessageException
{
    public static function execute(User $user, string $email, string $document) : void
    {
        if ($user->document == $document) {
            throw new \Exception('DOCUMENT_EXISTS', Response::HTTP_CONFLICT);
        }

        if ($user->email == $email) {
            throw new \Exception('EMAIL_EXISTS', Response::HTTP_CONFLICT);
        }
    }
}
