<?php

namespace Tests\Unit\Rules;

use App\Rules\DocumentRule;
use Faker\Factory;
use Faker\Provider\pt_BR\Company;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    public function testCpfHasLessNumbersReturnsFalse()
    {
        $documentRule = new DocumentRule();
        $response = $documentRule->passes( 'document', '23.214.555.7');
        $this->assertFalse($response);
    }

    public function testCnpjHasLessNumbersReturnsFalse()
    {
        $documentRule = new DocumentRule();
        $response = $documentRule->passes( 'document', '23.214.555.7');
        $this->assertFalse($response);
    }

    public function testCnpjOk()
    {
        $faker = Factory::create();
        $faker->addProvider(new Company($faker));

        $documentRule = new DocumentRule();
        $response = $documentRule->passes( 'document', $faker->cnpj);
        $this->assertTrue($response);
    }

    public function testCpfOk()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $documentRule = new DocumentRule();
        $response = $documentRule->passes( 'document', $faker->cpf);
        $this->assertTrue($response);
    }
}
