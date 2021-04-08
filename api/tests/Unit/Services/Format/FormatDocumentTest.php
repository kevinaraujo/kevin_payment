<?php

namespace Tests\Unit\Validation;

use App\Services\Format\FormatDocument;
use Faker\Factory;
use Faker\Provider\pt_BR\Company;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\TestCase;

class FormatDocumentTest extends TestCase
{
    public function testCnpjOk()
    {
        $faker = Factory::create();
        $faker->addProvider(new Company($faker));
        $document = $faker->cnpj;
        $expected = preg_replace('/\.|\-|\//', '', $document);

        $formatDocument = new FormatDocument($document);
        $response = $formatDocument->getDocument();

        $this->assertEquals($expected, $response);
    }

    public function testCpfOk()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        $document = $faker->cpf;
        $expected = preg_replace('/\.|\-|\//', '', $document);

        $formatDocument = new FormatDocument($document);
        $response = $formatDocument->getDocument();

        $this->assertEquals($expected, $response);
    }
}
