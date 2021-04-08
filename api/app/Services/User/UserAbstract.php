<?php

namespace App\Services\User;

abstract class UserAbstract
{
    protected $document;
    protected $code;

    public function __construct(string $document, string $code)
    {
        $this->document = $document;
        $this->code = $code;
    }

    abstract public function getDocument() : string;
    abstract public function getCode() : string;
}
