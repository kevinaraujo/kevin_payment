<?php

namespace App\Services\Document;

abstract class DocumentAbstract
{
    protected $document;

    public function __construct(string $document)
    {
        $this->document = $document;
    }

    abstract public function getDocument() : string;
}
