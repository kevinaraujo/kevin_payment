<?php

namespace App\Services\User;

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
    }
}

