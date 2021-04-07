<?php

namespace App\Services\Document;

class Cpf extends DocumentAbstract
{
    public function getDocument() : string
    {
        return $this->document;
    }
}
