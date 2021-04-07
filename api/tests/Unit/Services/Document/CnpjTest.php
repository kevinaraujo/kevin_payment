<?php

namespace Tests\Unit\Document;

use App\Services\Document\Cnpj;
use Faker\Factory;
use Faker\Provider\pt_BR\Company;
use PHPUnit\Framework\TestCase;

class CnpjTest extends TestCase
{
    public function testCnpjOk()
    {
        $faker = Factory::create();
        $faker->addProvider(new Company($faker));
        $cnpj = $faker->cnpj(false);

        $cnpjClass = new Cnpj($cnpj);
        $response = $cnpjClass->getDocument();

        $this->assertEquals($cnpj, $response);
    }
}
