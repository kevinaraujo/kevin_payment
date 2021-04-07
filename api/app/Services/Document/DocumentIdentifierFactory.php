<?php

namespace App\Services\Document;

class DocumentIdentifierFactory
{
    private $document;

    public function __construct(string $document)
    {
        $this->document = $document;
    }

    public function execute() : DocumentAbstract
    {
        switch (strlen($this->document)) {
            case 11:
                return (new Cpf($this->document));
            case 14:
                return (new Cnpj($this->document));
        }
    }
}

