<?php

namespace Tests\Unit\Services\User;

use App\Services\User\Client;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testClientOk() : void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        $cpf = $faker->cpf(false);
        $code = 'client';

        $cpfClass = new Client($cpf, $code);
        $expectedDocument = $cpfClass->getDocument();
        $expectedCode = $cpfClass->getCode();

        $this->assertEquals($cpf, $expectedDocument);
        $this->assertEquals($code, $expectedCode);
    }
}
