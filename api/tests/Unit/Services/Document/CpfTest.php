<?php

namespace Tests\Unit\Document;

use App\Services\Document\Cpf;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{
    public function testCpfOk()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        $cpf = $faker->cpf(false);

        $cpfClass = new Cpf($cpf);
        $response = $cpfClass->getDocument();

        $this->assertEquals($cpf, $response);
    }
}
