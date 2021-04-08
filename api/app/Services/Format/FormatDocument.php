<?php

namespace App\Services\Format;

class FormatDocument
{
    private $document;

    public function __construct(string $document)
    {
        $document = preg_replace('/\.|\-|\//', '', $document);
        $this->document = $document;
    }

    public function getDocument() : string
    {
        return $this->document;
    }
}
