<?php

namespace App\Services\User;

use Illuminate\Http\Response;

class UserIdentifierFactory
{
    public static function execute(string $document) : UserAbstract
    {
        switch (strlen($document)) {
            case 11:
                return (new Client($document, 'client'));
            case 14:
                return (new ShopKeeper($document, 'shopkeeper'));
        }

        throw new \Exception(
            'INVALID_DOCUMENT',
            Response::HTTP_BAD_REQUEST
        );
    }
}

