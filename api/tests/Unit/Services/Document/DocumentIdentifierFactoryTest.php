<?php

namespace Tests\Unit\Document;

use App\Services\Document\Cnpj;
use App\Services\Document\Cpf;
use App\Services\Document\DocumentIdentifierFactory;
use Faker\Factory;
use Faker\Provider\pt_BR\Company;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\TestCase;

class DocumentIdentifierFactoryTest extends TestCase
{
    public function testReturnCpfClass()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        $cpf = $faker->cpf(false);

        $identifierFactory = new DocumentIdentifierFactory($cpf);
        $class = $identifierFactory->execute();

        $this->assertInstanceOf(Cpf::class, $class);
    }

    public function testReturnCnpjClass()
    {
        $faker = Factory::create();
        $faker->addProvider(new Company($faker));
        $cnpj = $faker->cnpj(false);

        $identifierFactory = new DocumentIdentifierFactory($cnpj);
        $class = $identifierFactory->execute();

        $this->assertInstanceOf(Cnpj::class, $class);
    }
}
