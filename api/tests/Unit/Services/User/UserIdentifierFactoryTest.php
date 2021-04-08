<?php

namespace Tests\Unit\Services\User;

use App\Services\User\ShopKeeper;
use App\Services\User\Client;
use App\Services\User\UserIdentifierFactory;
use Faker\Factory;
use Faker\Provider\pt_BR\Company;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\TestCase;

class UserIdentifierFactoryTest extends TestCase
{
    public function testReturnClientClass() : void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        $cpf = $faker->cpf(false);

        $class = UserIdentifierFactory::execute($cpf);

        $this->assertInstanceOf(Client::class, $class);
    }

    public function testReturnShopKeeperClass() : void
    {
        $faker = Factory::create();
        $faker->addProvider(new Company($faker));
        $cnpj = $faker->cnpj(false);

        $class = UserIdentifierFactory::execute($cnpj);

        $this->assertInstanceOf(ShopKeeper::class, $class);
    }
}
