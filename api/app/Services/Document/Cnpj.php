<?php

namespace App\Services\Document;

class Cnpj extends DocumentAbstract
{
    public function getDocument() : string
    {
        return $this->document;
    }
}
