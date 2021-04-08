<?php

namespace Tests\Unit\Services\User;

use App\Services\User\ShopKeeper;
use Faker\Factory;
use Faker\Provider\pt_BR\Company;
use PHPUnit\Framework\TestCase;

class ShopKeeperTest extends TestCase
{
    public function testShopKeeperOk() : void
    {
        $faker = Factory::create();
        $faker->addProvider(new Company($faker));
        $cnpj = $faker->cnpj(false);
        $code = 'shopkeeper';

        $cnpjClass = new ShopKeeper($cnpj, $code);
        $expectedDocument = $cnpjClass->getDocument();
        $expectedCode = $cnpjClass->getCode();

        $this->assertEquals($cnpj, $expectedDocument);
        $this->assertEquals($code, $expectedCode);
    }
}
