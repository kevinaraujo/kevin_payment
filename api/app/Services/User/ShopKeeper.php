<?php

namespace App\Services\User;

class ShopKeeper extends UserAbstract
{
    public function getDocument() : string
    {
        return $this->document;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
