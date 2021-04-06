<?php

namespace Tests\Unit\Validation;

use App\Services\Validation\ValidateCpfCnpj;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testCpfHasLassThan11NumbersThrowsException()
    {
        $this->expectExceptionMessage('INVALID_CPF_CNPJ');

        $faker = Factory::create();
        $faker->addProvider(new Person($faker));

        $cpf = '23.214.555.7';
        $validateCpfCnpj = new ValidateCpfCnpj($cpf);
        $response = $validateCpfCnpj->execute();

    }
}
